<?php

namespace App\Console\Commands;

use App\Course;
use App\CourseLoader;
use App\Exceptions\ImportError;
use Illuminate\Console\Command;

class LoadCoursesFromJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:load {course?}';

    /**
     * Glob pattern for course json
     *
     * @var string
     */
    protected $coursePath = 'app/courses/*/course.json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load a course into the database. ' .
        'If no argument is specified, all courses will be loaded.';

    /**
     * The course loader service.
     *
     * @var CourseLoader
     */
    protected $courseLoader;

    /**
     * Create a new command instance.
     *
     * @param CourseLoader $courseLoader
     */
    public function __construct(CourseLoader $courseLoader)
    {
        parent::__construct();
        $this->courseLoader = $courseLoader;
    }

    protected function loadSingleCourse(Course $course)
    {
        if (is_null($course)) {
            $this->error('Course not found!');
            return;
        }
        $jsonFile = storage_path(str_replace('*', $course->name, $this->coursePath));
        if (!is_file($jsonFile)) {
            $this->error('Not found: ' . $jsonFile);
            return;
        }

        $dir = dirname($jsonFile);
        $this->courseLoader->importFromFolder($dir);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->courseLoader->setOutput($this->getOutput());

        $globPattern = storage_path($this->coursePath);
        if ($this->argument('course')) {
            $globPattern = str_replace('*', $this->argument('course'), $globPattern);
        }

        $importedCourses = 0;
        $failedCourses = 0;
        foreach (glob($globPattern) as $jsonFileName) {
            try {
                $dir = dirname($jsonFileName);
                $this->courseLoader->importFromFolder($dir);
                $importedCourses++;
            } catch (ImportError $e) {
                $this->error("Error: " . $e->getMessage());
                $failedCourses++;
            }
        }

        $this->comment("$importedCourses courses imported, $failedCourses failed.");
    }
}
