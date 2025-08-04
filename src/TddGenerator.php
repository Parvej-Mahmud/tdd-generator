<?php

namespace YourVendor\LaravelTddGenerator;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class TddGenerator
{
    protected $files;
    protected $config;

    public function __construct()
    {
        $this->files = new Filesystem();
        $this->config = config('tdd-generator', []);
    }

    public function generateModuleTdd(string $moduleName, array $options = [])
    {
        $moduleName = Str::studly($moduleName);
        
        $results = [];
        
        // Generate Model Test
        if ($options['model'] ?? true) {
            $results['model'] = $this->generateModelTest($moduleName, $options);
        }

        // Generate Controller Test
        if ($options['controller'] ?? true) {
            $results['controller'] = $this->generateControllerTest($moduleName, $options);
        }

        // Generate Migration Test
        if ($options['migration'] ?? true) {
            $results['migration'] = $this->generateMigrationTest($moduleName, $options);
        }

        // Generate Route Test
        if ($options['routes'] ?? true) {
            $results['routes'] = $this->generateRouteTest($moduleName, $options);
        }

        return $results;
    }

    public function generateModelTest(string $modelName, array $options = [])
    {
        $stub = $this->getStub('model.test.stub');
        $modelName = Str::studly($modelName);
        
        $replacements = [
            '{{ModelName}}' => $modelName,
            '{{modelName}}' => Str::camel($modelName),
            '{{model_name}}' => Str::snake($modelName),
            '{{table_name}}' => Str::plural(Str::snake($modelName)),
            '{{namespace}}' => $this->getTestNamespace('Unit'),
            '{{fillable_fields}}' => $this->generateFillableFields($modelName, $options),
            '{{test_data}}' => $this->generateTestData($modelName, $options),
        ];

        $content = str_replace(array_keys($replacements), array_values($replacements), $stub);
        
        $path = $this->getTestPath('Unit', "{$modelName}Test.php");
        
        $this->ensureDirectoryExists(dirname($path));
        $this->files->put($path, $content);

        return $path;
    }

    public function generateControllerTest(string $controllerName, array $options = [])
    {
        $stub = $this->getStub('controller.test.stub');
        $controllerName = Str::studly($controllerName);
        $modelName = str_replace('Controller', '', $controllerName);
        
        $replacements = [
            '{{ControllerName}}' => $controllerName,
            '{{ModelName}}' => $modelName,
            '{{modelName}}' => Str::camel($modelName),
            '{{model_name}}' => Str::snake($modelName),
            '{{route_prefix}}' => Str::plural(Str::kebab($modelName)),
            '{{namespace}}' => $this->getTestNamespace('Feature'),
            '{{test_methods}}' => $this->generateControllerTestMethods($modelName, $options),
        ];

        $content = str_replace(array_keys($replacements), array_values($replacements), $stub);
        
        $path = $this->getTestPath('Feature', "{$controllerName}Test.php");
        
        $this->ensureDirectoryExists(dirname($path));
        $this->files->put($path, $content);

        return $path;
    }

    public function generateMigrationTest(string $tableName, array $options = [])
    {
        $stub = $this->getStub('migration.test.stub');
        $tableName = Str::plural(Str::snake($tableName));
        $className = Str::studly($tableName);
        
        $replacements = [
            '{{ClassName}}' => $className,
            '{{table_name}}' => $tableName,
            '{{namespace}}' => $this->getTestNamespace('Unit'),
            '{{column_tests}}' => $this->generateColumnTests($tableName, $options),
        ];

        $content = str_replace(array_keys($replacements), array_values($replacements), $stub);
        
        $path = $this->getTestPath('Unit', "{$className}MigrationTest.php");
        
        $this->ensureDirectoryExists(dirname($path));
        $this->files->put($path, $content);

        return $path;
    }

    public function generateRouteTest(string $resourceName, array $options = [])
    {
        $stub = $this->getStub('route.test.stub');
        $resourceName = Str::studly($resourceName);
        $routePrefix = Str::plural(Str::kebab($resourceName));
        
        $replacements = [
            '{{ResourceName}}' => $resourceName,
            '{{route_prefix}}' => $routePrefix,
            '{{namespace}}' => $this->getTestNamespace('Feature'),
            '{{route_tests}}' => $this->generateRouteTestMethods($routePrefix, $options),
        ];

        $content = str_replace(array_keys($replacements), array_values($replacements), $stub);
        
        $path = $this->getTestPath('Feature', "{$resourceName}RouteTest.php");
        
        $this->ensureDirectoryExists(dirname($path));
        $this->files->put($path, $content);

        return $path;
    }

    protected function getStub(string $stubName): string
    {
        $stubPath = resource_path("stubs/tdd-generator/{$stubName}");
        
        if (!$this->files->exists($stubPath)) {
            $stubPath = __DIR__ . "/../stubs/{$stubName}";
        }

        return $this->files->get($stubPath);
    }

    protected function getTestNamespace(string $type): string
    {
        return "Tests\\{$type}";
    }

    protected function getTestPath(string $type, string $fileName): string
    {
        return base_path("tests/{$type}/{$fileName}");
    }

    protected function ensureDirectoryExists(string $directory): void
    {
        if (!$this->files->exists($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }
    }

    protected function generateFillableFields(string $modelName, array $options): string
    {
        // This would typically analyze the model or migration to determine fillable fields
        $commonFields = ['name', 'email', 'title', 'description', 'status'];
        return "'" . implode("', '", $commonFields) . "'";
    }

    protected function generateTestData(string $modelName, array $options): string
    {
        return "[\n            'name' => 'Test " . $modelName . "',\n            'email' => 'test@example.com',\n            'status' => 'active',\n        ]";
    }

    protected function generateControllerTestMethods(string $modelName, array $options): string
    {
        $methods = [
            'index' => 'test_can_list_' . Str::plural(Str::snake($modelName)),
            'show' => 'test_can_show_' . Str::snake($modelName),
            'store' => 'test_can_create_' . Str::snake($modelName),
            'update' => 'test_can_update_' . Str::snake($modelName),
            'destroy' => 'test_can_delete_' . Str::snake($modelName),
        ];

        $testMethods = '';
        foreach ($methods as $action => $methodName) {
            $testMethods .= $this->generateControllerTestMethod($action, $methodName, $modelName);
        }

        return $testMethods;
    }

    protected function generateControllerTestMethod(string $action, string $methodName, string $modelName): string
    {
        $routeName = Str::plural(Str::kebab($modelName));
        $modelVariable = Str::camel($modelName);
        
        switch ($action) {
            case 'index':
                return "
    public function {$methodName}()
    {
        \$response = \$this->get(route('{$routeName}.index'));
        \$response->assertStatus(200);
    }
";
            case 'show':
                return "
    public function {$methodName}()
    {
        \${$modelVariable} = {$modelName}::factory()->create();
        \$response = \$this->get(route('{$routeName}.show', \${$modelVariable}));
        \$response->assertStatus(200);
    }
";
            case 'store':
                return "
    public function {$methodName}()
    {
        \$data = {$modelName}::factory()->make()->toArray();
        \$response = \$this->post(route('{$routeName}.store'), \$data);
        \$response->assertStatus(201);
        \$this->assertDatabaseHas('{$routeName}', \$data);
    }
";
            case 'update':
                return "
    public function {$methodName}()
    {
        \${$modelVariable} = {$modelName}::factory()->create();
        \$data = {$modelName}::factory()->make()->toArray();
        \$response = \$this->put(route('{$routeName}.update', \${$modelVariable}), \$data);
        \$response->assertStatus(200);
        \$this->assertDatabaseHas('{$routeName}', \$data);
    }
";
            case 'destroy':
                return "
    public function {$methodName}()
    {
        \${$modelVariable} = {$modelName}::factory()->create();
        \$response = \$this->delete(route('{$routeName}.destroy', \${$modelVariable}));
        \$response->assertStatus(204);
        \$this->assertDatabaseMissing('{$routeName}', [\${$modelVariable}->getKeyName() => \${$modelVariable}->getKey()]);
    }
";
            default:
                return '';
        }
    }

    protected function generateColumnTests(string $tableName, array $options): string
    {
        return "
        \$this->assertTrue(Schema::hasTable('{$tableName}'));
        \$this->assertTrue(Schema::hasColumn('{$tableName}', 'id'));
        \$this->assertTrue(Schema::hasColumn('{$tableName}', 'created_at'));
        \$this->assertTrue(Schema::hasColumn('{$tableName}', 'updated_at'));";
    }

    protected function generateRouteTestMethods(string $routePrefix, array $options): string
    {
        $methods = ['GET', 'POST', 'PUT', 'DELETE'];
        $routes = [
            "GET /{$routePrefix}",
            "POST /{$routePrefix}",
            "GET /{$routePrefix}/{{$routePrefix}}",
            "PUT /{$routePrefix}/{{$routePrefix}}",
            "DELETE /{$routePrefix}/{{$routePrefix}}",
        ];

        $testMethods = '';
        foreach ($routes as $route) {
            $methodName = 'test_' . strtolower(explode(' ', $route)[0]) . '_' . str_replace(['/', '{', '}'], ['_', '', ''], explode(' ', $route)[1]) . '_route_exists';
            $testMethods .= "
    public function {$methodName}()
    {
        \$this->assertTrue(Route::has('{$routePrefix}.index') || Route::has('{$routePrefix}.show') || Route::has('{$routePrefix}.store') || Route::has('{$routePrefix}.update') || Route::has('{$routePrefix}.destroy'));
    }
";
        }

        return $testMethods;
    }
}