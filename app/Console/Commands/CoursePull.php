<?php

namespace App\Console\Commands;

use App\Course;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CoursePull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:pull {course}';

    /**
     * Path to course folder
     *
     * @var string
     */
    protected $coursePath = 'app/courses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull in changes to a course from remote git repo.';

    /**
     * Run a command and log the output.
     */
    protected function runCommand($cmd, $dir)
    {
        $process = new Process($cmd, $dir);
        $process->mustRun(); // Throws exception if command fails.
        $out = trim($process->getOutput());

        if (!empty($out)) {
            \Log::info($out);
            $this->line($out);
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $course = Course::where(['name' => $this->argument('course')])->first();
        if (is_null($course)) {
            $this->error('Course not found!');
            return;
        }
        $dir = storage_path("{$this->coursePath}/{$course->name}");
        if (!is_dir($dir)) {
            $this->error('Not a dir: ' . $dir);
            return;
        }

        \Log::info("Pulling in changes to the course '{$course->name}'");

        $this->comment("Course dir: $dir");

        $this->runCommand('git fetch origin', $dir);

        // If there's any local changes, we will stash them to be able to get them back later.
        $this->runCommand('git stash', $dir);

        // Using git reset --hard should also work if a force push was made to the repo.
        $this->runCommand('git reset --hard origin/master', $dir);
    }
}
