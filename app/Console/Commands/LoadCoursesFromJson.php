<?php

namespace App\Console\Commands;

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
    protected $signature = 'courses:load';

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
    protected $description = 'Load courses from json files into database.';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $importedCourses = 0;
        $failedCourses = 0;
        foreach (glob(storage_path($this->coursePath)) as $jsonFileName)
        {
            try {
                $dirName = dirname($jsonFileName);
                $this->comment("Importing course: " . basename($dirName));
                $this->courseLoader->importFromFolder($dirName);
                $importedCourses++;
            } catch (ImportError $e) {
                $this->error("Error: " . $e->getMessage());
                $failedCourses++;
            }
        }

        $this->comment("$importedCourses courses imported, $failedCourses failed.");
    }
}
