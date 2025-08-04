<?php

namespace YourVendor\LaravelTddGenerator\Tests\Feature;

use Orchestra\Testbench\TestCase;
use YourVendor\LaravelTddGenerator\TddGeneratorServiceProvider;
use Illuminate\Filesystem\Filesystem;

class TddGeneratorTest extends TestCase
{
    protected $files;

    protected function setUp(): void
    {
        parent::setUp();
        $this->files = new Filesystem();
    }

    protected function getPackageProviders($app)
    {
        return [TddGeneratorServiceProvider::class];
    }

    /** @test */
    public function it_can_generate_complete_tdd_suite()
    {
        $this->artisan('make:tdd', ['module' => 'TestModule'])
             ->assertExitCode(0);

        // Assert files were created
        $this->assertTrue($this->files->exists(base_path('tests/Unit/TestModuleTest.php')));
        $this->assertTrue($this->files->exists(base_path('tests/Feature/TestModuleControllerTest.php')));
    }

    /** @test */
    public function it_can_generate_model_test_only()
    {
        $this->artisan('make:model-test', ['model' => 'TestModel'])
             ->assertExitCode(0);

        $this->assertTrue($this->files->exists(base_path('tests/Unit/TestModelTest.php')));
    }

    /** @test */
    public function it_can_generate_controller_test_only()
    {
        $this->artisan('make:controller-test', ['controller' => 'TestController'])
             ->assertExitCode(0);

        $this->assertTrue($this->files->exists(base_path('tests/Feature/TestControllerTest.php')));
    }

    protected function tearDown(): void
    {
        // Clean up generated test files
        $testFiles = [
            base_path('tests/Unit/TestModuleTest.php'),
            base_path('tests/Feature/TestModuleControllerTest.php'),
            base_path('tests/Unit/TestModelTest.php'),
            base_path('tests/Feature/TestControllerTest.php'),
        ];

        foreach ($testFiles as $file) {
            if ($this->files->exists($file)) {
                $this->files->delete($file);
            }
        }

        parent::tearDown();
    }
}