<?php

namespace ParvejMahmud\LaravelTddGenerator\Commands;

use Illuminate\Console\Command;
use ParvejMahmud\LaravelTddGenerator\TddGenerator;

class GenerateMigrationTestCommand extends Command
{
    protected $signature = 'make:migration-test 
                           {table : The name of the table}
                           {--force : Overwrite existing files}';

    protected $description = 'Generate TDD test file for a Laravel migration';

    protected $generator;

    public function __construct(TddGenerator $generator)
    {
        parent::__construct();
        $this->generator = $generator;
    }

    public function handle()
    {
        $tableName = $this->argument('table');
        $options = ['force' => $this->option('force')];

        $this->info("Generating migration test for table: {$tableName}");

        try {
            $path = $this->generator->generateMigrationTest($tableName, $options);
            $this->info("✓ Generated migration test: {$path}");
        } catch (\Exception $e) {
            $this->error("Error generating migration test: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}