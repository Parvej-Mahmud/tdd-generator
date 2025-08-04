<?php

namespace YourVendor\LaravelTddGenerator\Commands;

use Illuminate\Console\Command;
use YourVendor\LaravelTddGenerator\TddGenerator;

class GenerateModelTestCommand extends Command
{
    protected $signature = 'make:model-test 
                           {model : The name of the model}
                           {--force : Overwrite existing files}';

    protected $description = 'Generate TDD test file for a Laravel model';

    protected $generator;

    public function __construct(TddGenerator $generator)
    {
        parent::__construct();
        $this->generator = $generator;
    }

    public function handle()
    {
        $modelName = $this->argument('model');
        $options = ['force' => $this->option('force')];

        $this->info("Generating model test for: {$modelName}");

        try {
            $path = $this->generator->generateModelTest($modelName, $options);
            $this->info("✓ Generated model test: {$path}");
        } catch (\Exception $e) {
            $this->error("Error generating model test: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}