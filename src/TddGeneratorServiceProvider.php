<?php

namespace ParvejMahmud\LaravelTddGenerator;

use Illuminate\Support\ServiceProvider;
use ParvejMahmud\LaravelTddGenerator\Commands\GenerateTddCommand;
use ParvejMahmud\LaravelTddGenerator\Commands\GenerateModelTestCommand;
use ParvejMahmud\LaravelTddGenerator\Commands\GenerateControllerTestCommand;
use ParvejMahmud\LaravelTddGenerator\Commands\GenerateMigrationTestCommand;
use ParvejMahmud\LaravelTddGenerator\Commands\GenerateRouteTestCommand;

class TddGeneratorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('tdd-generator', function () {
            return new TddGenerator();
        });
    }

    public function boot()
    {
        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateTddCommand::class,
                GenerateModelTestCommand::class,
                GenerateControllerTestCommand::class,
                GenerateMigrationTestCommand::class,
                GenerateRouteTestCommand::class,
            ]);
        }

        // Publish configuration
        $this->publishes([
            __DIR__.'/../config/tdd-generator.php' => config_path('tdd-generator.php'),
        ], 'config');

        // Publish stubs
        $this->publishes([
            __DIR__.'/../stubs' => resource_path('stubs/tdd-generator'),
        ], 'stubs');

        // Load configuration
        $this->mergeConfigFrom(__DIR__.'/../config/tdd-generator.php', 'tdd-generator');
    }
}