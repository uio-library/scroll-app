<?php

namespace App;

use App\Exceptions\ImportError;
use Michelf\MarkdownExtra;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CourseLoader
{
    public function __construct(MarkdownExtra $markdown)
    {
        $this->markdown = $markdown;
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
        $jsonPath = "$dirname/course.json";
        $modulesPath = "$dirname/modules/*.md";
        $resourcesPath = "$dirname/resources/*";
        $exercisesPath = "$dirname/exercises/*.json";

        $commit = trim($this->runCommand('git rev-parse HEAD', $dirname));
        echo "git HEAD is at $commit\n";

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
        $course->commit = $commit;
        $this->addModuleTexts($course, $moduleBlobs);
        $course->save();

        $this->loadExercises($course, $exercises);

        foreach (glob($resourcesPath) as $srcPath) {
            if (!is_file($srcPath)) {
                continue;
            }
            $relPath = basename($srcPath);
            $dstPath = storage_path("app/public/{$course->id}/$relPath");
            print("Linked resource: $dstPath \n");

            if (!is_dir(dirname($dstPath))) {
                mkdir(dirname($dstPath), 0775, true);
            }
            copy($srcPath, $dstPath);
        }

        \Log::info("Imported course '{$course->name}'@{$commit}");

        return $course;
    }

    /**
     * Set data on course or exercise object.
     *
     * @param $obj
     * @param $data
     * @param $props
     * @throws ImportError
     */
    protected function setData($obj, $data, $props)
    {
        foreach ($props as $prop) {
            if (!isset($data->{$prop})) {
                throw new ImportError("No '$prop' field found in {$obj->name}");
            }
            $obj->{$prop} = $data->{$prop};
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
        $props = ['header', 'headertext', 'footer', 'modules', 'repo'];

        $course = Course::firstOrNew(['name' => $courseName]);
        $this->setData($course, $data, $props);

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
        $props = ['type', 'content', 'answer'];

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
