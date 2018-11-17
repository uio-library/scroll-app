<?php

namespace App;

use App\Exceptions\ImportError;
use Carbon\Carbon;
use Despark\ImagePurify\Interfaces\ImagePurifierInterface;
use Illuminate\Console\Command;
use Michelf\MarkdownExtra;
use Swaggest\JsonSchema\Schema;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CourseLoader
{
    protected $schemaVersion = 2;
    protected $schema;

    public function __construct(MarkdownExtra $markdown, ImagePurifierInterface $purifier)
    {
        $this->markdown = $markdown;
        $this->purifier = $purifier;
        $this->schema = Schema::import(json_decode(file_get_contents(resource_path('course.schema.json'))));
    }

    public function setOutput(\Illuminate\Console\OutputStyle $out)
    {
        $this->out = $out;
    }

    protected function log($msg)
    {
        if (isset($this->out)) {
            $this->out->writeln($msg);
        }
        \Log::info($msg);
    }

    /**
     * Run a command and return the output.
     */
    protected function runCommand($cmd, $dir)
    {
        $process = new Process($cmd, $dir);
        $process->mustRun(); // Throws exception if command fails.
        return $process->getOutput();
    }

    /**
     * @param string $dirname
     * @throws ImportError
     */
    public function importFromFolder($dirname)
    {
        $this->log("Importing course: {$dirname}");

        $jsonPath = "$dirname/course.json";
        $modulesPath = "$dirname/modules/*.md";
        $resourcesPath = "$dirname/resources/*";
        $exercisesPath = "$dirname/exercises/*.json";

        $commitHash = trim($this->runCommand('git rev-parse HEAD', $dirname));
        $commitDate = trim($this->runCommand('git log -1 --format=%cd --date=iso', $dirname));
        $commitDate = new Carbon($commitDate);

        $courseName = basename($dirname);
        $courseData = json_decode(file_get_contents($jsonPath));
        if (is_null($courseData)) {
            throw new ImportError('Not a valid JSON file');
        }

        $moduleBlobs = [];
        foreach (glob($modulesPath) as $modulePath) {
            $moduleName = basename($modulePath, '.md');
            $moduleBlobs[$moduleName] = file_get_contents($modulePath);
        }

        $exercises = [];
        foreach (glob($exercisesPath) as $exercisePath) {
            $exerciseName = basename($exercisePath, '.json');
            $exercisedata = json_decode(file_get_contents($exercisePath));
            if (is_null($exercisedata)) {
                throw new ImportError("Exercise '$exerciseName' is not a valid JSON file.'");
            }
            $exercises[$exerciseName] = $exercisedata;
        }

        $course = $this->courseFromJson($courseName, $courseData);
        $course->last_commit = $commitHash;
        $course->last_commit_at = $commitDate;
        $this->addModuleTexts($course, $moduleBlobs);
        $course->save();

        $this->loadExercises($course, $exercises);

        $this->log("Copying and compressing resources");
        $filesizeSum = 0;
        foreach (glob($resourcesPath) as $srcPath) {
            if (!is_file($srcPath)) {
                continue;
            }
            $relPath = basename($srcPath);
            $dstPath = storage_path("app/public/{$course->id}/$relPath");

            if (!is_dir(dirname($dstPath))) {
                mkdir(dirname($dstPath), 0775, true);
            }

            list($width, $height) = getimagesize($srcPath);
            $maxWidth = 1920;
            if ($width > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = $height * $newWidth / $width;
                $img = \Image::make($srcPath)
                    ->resize($newWidth, $newHeight)
                    ->save($dstPath);

                $this->log(" - {$relPath}: scaled from {$width}x{$height}px to {$newWidth}x{$newHeight}px");
            } else {
                copy($srcPath, $dstPath);
            }

            $this->purifier->purify($dstPath);

            $srcSize = round(filesize($srcPath) / 1024);
            $dstSize = round(filesize($dstPath) / 1024);
            $filesizeSum += $dstSize;

            $this->log(" - {$relPath}: filesize reduced from {$srcSize} KB to {$dstSize} KB");
        }

        $this->log("Total size of resources: {$filesizeSum} kB");

        $this->log("Imported course '{$course->name}'@{$commitHash}");

        return $course;
    }

    /**
     * Set data on course or exercise object.
     *
     * @param $obj
     * @param $data
     * @param $props
     * @param $optionalProps
     * @throws ImportError
     */
    protected function setData($obj, $data, $props, $optionalProps = [])
    {
        foreach ($props as $prop) {
            if (!isset($data->{$prop})) {
                throw new ImportError("No '$prop' field found in {$obj->name}");
            }
            $obj->{$prop} = $data->{$prop};
        }
        foreach ($optionalProps as $prop) {
            if (isset($data->{$prop})) {
                $obj->{$prop} = $data->{$prop};
            }
        }
    }

    /**
     * Load a Course object from a course.json file.
     *
     * @param string    $courseName
     * @param \stdClass $data
     * @return Course;
     * @throws ImportError
     */
    public function courseFromJson($courseName, $data)
    {
        if (($data->version ?? 0) !== $this->schemaVersion) {
            throw new ImportError(sprintf(
                'This version of Scroll requires a course.json schema version %d.',
                $this->schemaVersion
            ));
        }

        // Validate
        $this->schema->in($data);

        $props = [
            'header',
            'footer',
            'modules',
            'repo',
        ];

        $optionalProps = [
            'options',
            'lang',
        ];

        $course = Course::firstOrNew(['name' => $courseName]);
        $this->setData($course, $data, $props, $optionalProps);

        return $course;
    }

    /**
     * Load an Exercise object from a *.json file.
     *
     * @param Course    $course
     * @param string    $exerciseName
     * @param \stdClass $data
     * @return Exercise;
     * @throws ImportError
     */
    public function exerciseFromJson(Course $course, $exerciseName, $data)
    {
        $props = [
            'type',
            'content',
            'answer',
        ];

        $exercise = Exercise::firstOrNew([
            'course_id' => $course->id,
            'name' => $exerciseName,
        ]);
        $this->setData($exercise, $data, $props);

        return $exercise;
    }

    /**
     * Load a list of exercises.
     *
     * @param Course $course
     * @param        $exercises
     * @throws ImportError
     */
    protected function loadExercises(Course $course, $exercises)
    {
        foreach ($exercises as $name => $data) {
            $exercise = $this->exerciseFromJson($course, $name, $data);
            $exercise->save();
        }
    }
    /**
     * Load Markdown text for course modules.
     *
     * @param Course   $course
     * @param string[] $blobs
     * @throws ImportError
     */
    protected function addModuleTexts(Course &$course, $blobs)
    {
        $modules = [];
        foreach ($course->modules as $module) {
            if (!isset($blobs[$module->id])) {
                throw new ImportError("No Markdown data found for the module '{$module->id}'");
            }

            // Store original markdown
            $module->content = $blobs[$module->id];

            // and cache the html output
            $module->html = $this->markdown->transform($module->content);

            $modules[] = $module;
        }
        $course->modules = $modules;
    }
}
