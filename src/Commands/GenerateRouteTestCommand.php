<?php

namespace ParvejMahmud\LaravelTddGenerator\Commands;

use Illuminate\Console\Command;
use ParvejMahmud\LaravelTddGenerator\TddGenerator;

class GenerateRouteTestCommand extends Command
{
    protected $signature = 'make:route-test 
                           {resource : The name of the resource}
                           {--force : Overwrite existing files}';

    protected $description = 'Generate TDD test file for Laravel routes';

    protected $generator;

    public function __construct(TddGenerator $generator)
    {
        parent::__construct();
        $this->generator = $generator;
    }

    public function handle()
    {
        $resourceName = $this->argument('resource');
        $options = ['force' => $this->option('force')];

        $this->info("Generating route test for resource: {$resourceName}");

        try {
            $path = $this->generator->generateRouteTest($resourceName, $options);
            $this->info("✓ Generated route test: {$path}");
        } catch (\Exception $e) {
            $this->error("Error generating route test: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}