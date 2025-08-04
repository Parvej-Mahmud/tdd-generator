<?php

namespace YourVendor\LaravelTddGenerator\Commands;

use Illuminate\Console\Command;
use YourVendor\LaravelTddGenerator\TddGenerator;

class GenerateControllerTestCommand extends Command
{
    protected $signature = 'make:controller-test 
                           {controller : The name of the controller}
                           {--force : Overwrite existing files}';

    protected $description = 'Generate TDD test file for a Laravel controller';

    protected $generator;

    public function __construct(TddGenerator $generator)
    {
        parent::__construct();
        $this->generator = $generator;
    }

    public function handle()
    {
        $controllerName = $this->argument('controller');
        $options = ['force' => $this->option('force')];

        $this->info("Generating controller test for: {$controllerName}");

        try {
            $path = $this->generator->generateControllerTest($controllerName, $options);
            $this->info("✓ Generated controller test: {$path}");
        } catch (\Exception $e) {
            $this->error("Error generating controller test: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}