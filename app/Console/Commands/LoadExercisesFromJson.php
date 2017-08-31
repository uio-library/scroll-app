<?php

namespace App\Console\Commands;

use App\Exercise;
use App\Exceptions\ImportError;
use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid;

class LoadExercisesFromJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exercises:load';

    /**
     * Glob pattern for exercises
     *
     * @var string
     */
    protected $exercisePath = 'app/oppgaver/*.json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load exercises from json files into database.';

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

        if (!isset($data->id)) {
            $exercise = new Exercise();
            $exercise->id = Uuid::uuid1()->toString();
            $this->setData($exercise, $data);
            $exercise->save();
            $data->first_import_time = (string) $exercise->created_at;
            $data->id = $exercise->id;
            file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        } else {
            $exercise = Exercise::firstOrNew(["id" => $data->id]);
            $this->setData($exercise, $data);
            $exercise->save();
        }
    }

    /**
     * Set data on exercise.
     *
     * @return boolean
     */
    public function setData($exercise, $data)
    {
        $fields = ['content', 'answer', 'name', 'type'];
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

        $this->info("$imported exercises imported, $failed failed.");
    }
}
