<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class VarnishPurge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'varnish:purge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge Varnish cache';

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
        $command = [
            config('varnish.adm_path'),
            'ban req.http.host == "' . config('varnish.domain') . '"',
        ];
        $process = new Process($command);
        $process->mustRun();
        $this->line($process->getOutput());
    }
}
