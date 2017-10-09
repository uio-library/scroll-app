<?php

namespace App\Console\Commands;

use App\Exercise;
use App\Course;
use App\Exceptions\ImportError;
use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid;

class LoadCoursesFromJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'courses:load';

    /**
     * Glob pattern for exercises
     *
     * @var string
     */
    protected $exercisePath = 'app/exercises/*.json';

    /**
     * Glob pattern for course html
     *
     * @var string
     */
    protected $coursePath = 'app/courses/*';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load courses from json files into database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Import a single exercise.
     *
     * @return void
     */
    public function importExercise($file)
    {
        $data = json_decode(file_get_contents($file));
        if (is_null($data)) {
            throw new ImportError('Not a valid JSON file');
        }
        $fields = ['content', 'answer', 'name', 'type'];
        if (!isset($data->id)) {
            $exercise = new Exercise();
            $exercise->id = Uuid::uuid1()->toString();  
            $this->setData($exercise, $data, $fields);
            $exercise->save();
            $data->first_import_time = (string) $exercise->created_at;
            $data->id = $exercise->id;
            file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        } else {
            $exercise = Exercise::firstOrNew(["id" => $data->id]);
            $this->setData($exercise, $data, $fields);
            $exercise->save();
        }
    }

    /**
     * Import a single course.
     *
     * @return void
     */
    public function importCourse($folder, $file)
    {
        $data = json_decode(file_get_contents("$folder/$file"));
        if (is_null($data)) {
            throw new ImportError('Not a valid JSON file');
        }
        $fields = ['modules', 'name'];
        if (!isset($data->name)) {
            $course = new Course();
        } else {
            $course = Course::firstOrNew(["name" => $data->name, "header" => $data->header, "headertext" => $data->headertext]);
        }
        $this->setData($course, $data, $fields);
        $this->loadCourseHtml($course, $folder);
        $course->save();
    }

    /**
     * Set html on course modules.
     *
     * @return boolean
     */

    protected function loadCourseHtml(Course $course, $folder)
    {
        $modules = [];
        foreach ($course->modules as $module)
        {
            $filepath = "$folder/{$module->id}.html";
            $this->info($filepath);
            $module->html = file_get_contents($filepath);
            $modules[] = $module;
        }
        $course->modules = $modules;
    }

    /**
     * Set data on exercise.
     *
     * @return boolean
     */
    protected function setData($exercise, $data, $fields)
    {
        foreach ($fields as $field) {
            if (!isset($data->{$field})) {
                throw new ImportError("Field missing: '$field'");
            }
            $exercise->{$field} = $data->{$field};
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Loading exercises from: $this->exercisePath");

        $imported = 0;
        $failed = 0;
        foreach (glob(storage_path($this->exercisePath)) as $filename)
        {
            try {
                $this->importExercise($filename);
                $imported++;
            } catch (ImportError $e) {
                $this->warn("Error: Failed to import $filename: " . $e->getMessage());
                $failed++;
            }
        }

        $importedCourses = 0;
        $failedCourses = 0;
        foreach (glob(storage_path($this->coursePath)) as $foldername)
        {
            if (is_dir($foldername))
            {
                $jsonFileName = "course.json";
                try {

                    $this->importCourse($foldername, $jsonFileName);
                    $importedCourses++;
                } catch (ImportError $e) {
                    $this->warn("Error: Failed to import $jsonFileName: " . $e->getMessage());
                    $failedCourses++;
                }
            }   
        }

        $this->info("$importedCourses courses imported, $failedCourses failed.");
        $this->info("$imported exercises imported, $failed failed.");
    }
}
