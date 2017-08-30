<?php

namespace App\Console\Commands;

use App\Exercise;
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
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load exercises from a folder containing json files to database. Deletes contents of database';

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
        print(app_path());
        foreach (glob(storage_path("app/oppgaver/*.json")) as $file)
        {
            print($file."\n");
            $data = json_decode(file_get_contents($file));
            if (is_null($data)) {
                $this->warn("Warning: Could not read file ".$file);
                continue;
            }

            if (!isset($data->id)) {
                $exercise = new Exercise();
                $exercise->id = Uuid::uuid1()->toString();
                $exercise->content = $data->content;
                $exercise->answer = $data->answer;
                $exercise->type = $data->type;
                $exercise->name = $data->name;
                $exercise->save();
                $data->first_import_time = (string) $exercise->created_at;
                $data->id = $exercise->id;
                file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
            }
            else {
                print("Creating: ".$data->name."\n");
                $exercise = Exercise::firstOrNew(["id" => $data->id]);
                $exercise->content = $data->content;
                $exercise->answer = $data->answer;
                $exercise->type = $data->type;
                $exercise->name = $data->name;
                $exercise->save();
            }
        }
    }
}
