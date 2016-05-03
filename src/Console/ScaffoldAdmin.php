<?php

namespace Webup\LaravelScaffold\Console;

use Illuminate\Console\Command;

class ScaffoldAdmin extends Command
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
        $this->createDirectories();

        $files = [
            'console/AdminCreate.stub' => 'app/Console/Commands/AdminCreate.php',
            'console/AdminDelete.stub' => 'app/Console/Commands/AdminDelete.php',
            'console/AdminList.stub' => 'app/Console/Commands/AdminList.php',
            'console/AdminUpdate.stub' => 'app/Console/Commands/AdminUpdate.php',
            'controllers/AuthController.stub' => 'app/Http/Controllers/Admin/AuthController.php',
            'entities/AdminUser.stub' => 'app/Entities/AdminUser.php',
            'middleware/AdminAuthenticate.stub' => 'app/Http/Middleware/AdminAuthenticate.php',
            'database/2016_05_03_000000_create_admin_users_table.stub' => 'database/migrations/2016_05_03_000000_create_admin_users_table.php',
            'providers/AdminServiceProvider.stub' => 'app/Providers/AdminServiceProvider.php',
            'views/auth/login.stub' => 'resources/views/admin/auth/login.blade.php',
            'views/layouts/auth.stub' => 'resources/views/admin/layouts/auth.blade.php',
            'views/layouts/master.stub' => 'resources/views/admin/layouts/master.blade.php',
        ];

        $stubPath = dirname(__DIR__).'/stubs/admin/';

        foreach ($files as $stub => $path) {
            $destPath = base_path($path);
            $this->line('<info>Created View:</info> '.$destPath);
            copy($stubPath.$stub, $destPath);
        }

        $this->updateRoutes();

        $this->comment("Admin generated");
        $this->printReadme();
    }

    protected function createDirectories()
    {
        if (! is_dir(base_path('app/Http/Controllers/Admin'))) {
            mkdir(base_path('app/Http/Controllers/Admin'), 0755, true);
        }

        if (! is_dir(base_path('app/Entities'))) {
            mkdir(base_path('app/Entities'), 0755, true);
        }

        if (! is_dir(base_path('resources/views/admin/auth'))) {
            mkdir(base_path('resources/views/admin/auth'), 0755, true);
        }

        if (! is_dir(base_path('resources/views/admin/layouts'))) {
            mkdir(base_path('resources/views/admin/layouts'), 0755, true);
        }
    }

    protected function updateRoutes()
    {
        $stubPath = dirname(__DIR__).'/stubs/admin/';

        file_put_contents(
            app_path('Http/routes.php'),
            file_get_contents($stubPath.'routes.stub'),
            FILE_APPEND
        );

        $this->info('Updated Routes File.');
    }

    protected function printReadme()
    {
        $this->line("\nREADME\n");

        $this->line("\nProvider\n");
        $this->line("App\Providers\AdminServiceProvider::class,");

        $this->line("\nconfig/auth.php\n");


        $this->line("'guards' => [");
        $this->line("    'admin' => [");
        $this->line("        'driver' => 'session',");
        $this->line("        'provider' => 'admins',");
        $this->line("    ],");
        $this->line("],");

        $this->line("'providers' => [");
        $this->line("    'admins' => [");
        $this->line("        'driver' => 'eloquent',");
        $this->line("        'model' => App\Entities\AdminUser::class,");
        $this->line("    ],");
        $this->line("],");

        $this->line("\nBower\n");
        $this->line("bower install admin-lte#2.3.3 --save");
        $this->line("bower install font-awesome#4.4.0 --save");
        $this->line("bower install ionicons#2.0.1 --save");
    }
}
