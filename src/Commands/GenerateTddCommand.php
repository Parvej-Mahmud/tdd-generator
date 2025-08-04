<?php

namespace YourVendor\LaravelTddGenerator\Commands;

use Illuminate\Console\Command;
use YourVendor\LaravelTddGenerator\TddGenerator;

class GenerateTddCommand extends Command
{
    protected $signature = 'make:tdd 
                           {module : The name of the module/feature}
                           {--model : Generate model tests}
                           {--controller : Generate controller tests}
                           {--migration : Generate migration tests}
                           {--routes : Generate route tests}
                           {--all : Generate all test types}
                           {--force : Overwrite existing files}';

    protected $description = 'Generate comprehensive TDD files for a Laravel module';

    protected $generator;

    public function __construct(TddGenerator $generator)
    {
        parent::__construct();
        $this->generator = $generator;
    }

    public function handle()
    {
        $moduleName = $this->argument('module');
        
        $options = [
            'model' => $this->option('model') || $this->option('all'),
            'controller' => $this->option('controller') || $this->option('all'),
            'migration' => $this->option('migration') || $this->option('all'),
            'routes' => $this->option('routes') || $this->option('all'),
            'force' => $this->option('force'),
        ];

        // If no specific options are provided, generate all
        if (!$options['model'] && !$options['controller'] && !$options['migration'] && !$options['routes']) {
            $options = array_merge($options, [
                'model' => true,
                'controller' => true,
                'migration' => true,
                'routes' => true,
            ]);
        }

        $this->info("Generating TDD files for module: {$moduleName}");

        try {
            $results = $this->generator->generateModuleTdd($moduleName, $options);

            foreach ($results as $type => $path) {
                if ($path) {
                    $this->info("✓ Generated {$type} test: {$path}");
                }
            }

            $this->info("\n🎉 TDD files generated successfully!");
            $this->comment("Run 'php artisan test' to execute your tests.");

        } catch (\Exception $e) {
            $this->error("Error generating TDD files: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}