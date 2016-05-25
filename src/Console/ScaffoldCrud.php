<?php

namespace Webup\LaravelScaffold\Console;

use Illuminate\Console\Command;

class ScaffoldCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffold:crud {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a crud for admin';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = studly_case($this->argument('name'));
        $vars = $this->getVars($this->argument('name'));

        $this->createDirectories($vars);

        $this->call('make:model', [
            'name' => 'Entities/'.$name,
            '--migration' => true,
        ]);

        $files = [
            'Controller.stub' => "app/Http/Controllers/Admin/{$vars['DummiesController']}.php",
            'Repository.stub' => "app/Repositories/{$vars['DummyRepository']}.php",
            'views/create.stub' => "resources/views/admin/{$vars['dummies']}/create.blade.php",
            'views/edit.stub' => "resources/views/admin/{$vars['dummies']}/edit.blade.php",
            'views/index.stub' => "resources/views/admin/{$vars['dummies']}/index.blade.php",
        ];

        $stubPath = dirname(__DIR__).'/stubs/crud/';

        foreach ($files as $stub => $path) {
            $destPath = base_path($path);
            $this->line('<info>Create file:</info> '.$destPath);

            $content = str_replace(
                array_keys($vars),
                array_values($vars),
                file_get_contents($stubPath.$stub)
            );

            file_put_contents($destPath, $content);
        }

        $this->updateRoutes($vars);
    }

    protected function createDirectories($vars)
    {
        if (! is_dir(base_path('app/Http/Controllers/Admin'))) {
            mkdir(base_path('app/Http/Controllers/Admin'), 0755, true);
        }

        if (! is_dir(base_path('app/Repositories'))) {
            mkdir(base_path('app/Repositories'), 0755, true);
        }

        if (! is_dir(base_path('resources/views/admin'))) {
            mkdir(base_path('resources/views/admin'), 0755, true);
        }

        if (! is_dir(base_path('resources/views/admin/'.$vars['dummies']))) {
            mkdir(base_path('resources/views/admin/'.$vars['dummies']), 0755, true);
        }
    }

    protected function getVars($name)
    {
        $name = studly_case($name);

        return [
            'Dummies' => str_plural($name),
            'dummies' => str_plural(camel_case($name)),
            'DummiesController' => str_plural($name) . 'Controller',
            'Dummy' => $name,
            'dummy' => camel_case($name),
            'DummyRepository' => $name . 'Repository',
            'dummyRepository' => camel_case($name) . 'Repository',
        ];
    }

    protected function updateRoutes($vars)
    {
        $stubPath = dirname(__DIR__).'/stubs/crud/';

        $content = str_replace(
            array_keys($vars),
            array_values($vars),
            file_get_contents($stubPath.'routes.stub')
        );

        file_put_contents(
            app_path('Http/routes.php'),
            $content,
            FILE_APPEND
        );

        $this->info('Updated Routes File.');
    }
}
