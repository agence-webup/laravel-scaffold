<?php

namespace Webup\LaravelScaffold\Console;

use Illuminate\Console\Command;

class Admin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffold:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate admin files';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment("Create Admin\n");
    }
}
