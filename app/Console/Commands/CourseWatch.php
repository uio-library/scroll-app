<?php

namespace App\Console\Commands;

use App\Course;
use Illuminate\Console\Command;
use Lurker\Event\FilesystemEvent;
use Lurker\ResourceWatcher;

class CourseWatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:watch {course?}';

    /**
     * Glob pattern for course json
     *
     * @var string
     */
    protected $coursePath = 'app/courses/';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Watch for changes to course files and re-import a course that has changed.';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $watcher = new ResourceWatcher();

        if (empty($this->argument('course'))) {
            $dir = storage_path($this->coursePath);

            $watcher->track('courses', $dir);

            $this->comment("Watching: $dir, press Ctrl-C to stop.");

            $watcher->addListener('courses', function (FilesystemEvent $event) {
                $this->comment($event->getResource() . ': ' . $event->getTypeString());

                $relPath = explode('/', trim(substr($event->getResource(), strlen(storage_path($this->coursePath)))));
                $firstDir = array_shift($relPath);
                $this->comment("Course changed: $firstDir");
                $this->call('course:load', ['course' => $firstDir]);

                // Touch some file so that browser-sync updates the browser
                touch(resource_path('views/layout.blade.php'));
            });
        } else {
            $course = Course::where(['name' => $this->argument('course')])->first();
            if (is_null($course)) {
                $this->error('Course not found!');
                return;
            }
            $dir = storage_path($this->coursePath . '/' . $course->name);
            if (!is_dir($dir)) {
                $this->error('Not a dir: ' . $dir);
                return;
            }

            $watcher->track('course', $dir);

            $this->comment("Watching: $dir, press Ctrl-C to stop.");

            $watcher->addListener('course', function (FilesystemEvent $event) use ($course) {
                echo $event->getResource() . ': ' . $event->getTypeString() . "\n";
                $this->call('course:load', ['course' => $course->name]);

                // Touch some file so that browser-sync updates the browser
                touch(resource_path('views/layout.blade.php'));
            });
        }

        $watcher->start();
    }
}
