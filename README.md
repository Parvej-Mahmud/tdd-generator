# Laravel TDD Generator

A powerful Laravel package that automatically generates comprehensive Test-Driven Development (TDD) files for any given module or feature. This package creates well-structured test files for models, controllers, migrations, and routes with a single command.

## Features

- 🚀 **One Command, Complete TDD Suite**: Generate all test files for a module with one command
- 🧪 **Comprehensive Test Coverage**: Creates tests for models, controllers, migrations, and routes
- 🎯 **Smart Test Generation**: Automatically detects common patterns and generates appropriate tests
- 🔧 **Highly Customizable**: Customizable stubs and configurations
- 📝 **Best Practices**: Follows Laravel testing best practices and conventions
- 🏗️ **Multiple Test Types**: Supports unit, feature, and integration tests
- 🔒 **Security Aware**: Includes authentication and authorization tests

## Installation

Install the package via Composer:

```bash
composer require parvej-mahmud/laravel-tdd-generator --dev
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="ParvejMahmud\LaravelTddGenerator\TddGeneratorServiceProvider" --tag="config"
```

Optionally, publish the stub files for customization:

```bash
php artisan vendor:publish --provider="ParvejMahmud\LaravelTddGenerator\TddGeneratorServiceProvider" --tag="stubs"
```

## Usage

### Generate Complete TDD Suite

Generate all test files for a module:

```bash
php artisan make:tdd Post
```

This creates:
- `tests/Unit/PostTest.php` - Model tests
- `tests/Feature/PostControllerTest.php` - Controller tests
- `tests/Unit/PostsMigrationTest.php` - Migration tests
- `tests/Feature/PostRouteTest.php` - Route tests

### Generate Specific Test Types

Generate only specific test types:

```bash
# Generate only model tests
php artisan make:tdd Post --model

# Generate only controller tests
php artisan make:tdd Post --controller

# Generate multiple specific types
php artisan make:tdd Post --model --controller
```

### Individual Commands

You can also use individual commands for more control:

```bash
# Generate model test only
php artisan make:model-test Post

# Generate controller test only
php artisan make:controller-test PostController

# Generate migration test only
php artisan make:migration-test posts

# Generate route test only
php artisan make:route-test Post
```

### Command Options

- `--model`: Generate model tests
- `--controller`: Generate controller tests
- `--migration`: Generate migration tests
- `--routes`: Generate route tests
- `--all`: Generate all test types (default behavior)
- `--force`: Overwrite existing files

## Generated Tests

### Model Tests

The generated model tests include:
- ✅ CRUD operations (Create, Read, Update, Delete)
- ✅ Fillable attributes validation
- ✅ Required fields validation
- ✅ Relationships testing
- ✅ Timestamps verification
- ✅ Factory integration

### Controller Tests

The generated controller tests include:
- ✅ All CRUD endpoints (index, show, store, update, destroy)
- ✅ Request validation testing
- ✅ Authentication and authorization
- ✅ Error handling (404, 422, etc.)
- ✅ Pagination testing
- ✅ Search functionality

### Migration Tests

The generated migration tests include:
- ✅ Table creation verification
- ✅ Column existence and types
- ✅ Primary key validation
- ✅ Timestamps verification
- ✅ Constraints testing
- ✅ Data insertion capabilities

### Route Tests

The generated route tests include:
- ✅ Route existence verification
- ✅ HTTP method validation
- ✅ URL generation testing
- ✅ Middleware verification
- ✅ Authentication requirements
- ✅ Error handling

## Configuration

The package comes with a comprehensive configuration file. Here are the key options:

```php
// config/tdd-generator.php

return [
    // Default test types to generate
    'default_types' => [
        'model' => true,
        'controller' => true,
        'migration' => true,
        'routes' => true,
    ],

    // Test namespaces
    'namespaces' => [
        'unit' => 'Tests\\Unit',
        'feature' => 'Tests\\Feature',
    ],

    // Authentication requirements
    'auth_required' => [
        'store' => true,
        'update' => true,
        'destroy' => true,
    ],
];
```

## Customizing Stubs

You can customize the generated test files by publishing and modifying the stub files:

```bash
php artisan vendor:publish --provider="ParvejMahmud\LaravelTddGenerator\TddGeneratorServiceProvider" --tag="stubs"
```

The stub files will be published to `resources/stubs/tdd-generator/` where you can modify them to match your project's coding standards.

## Examples

### Basic Usage

```bash
# Generate complete TDD suite for a User module
php artisan make:tdd User

# Generate tests for an API resource
php artisan make:tdd Product --controller --routes

# Force overwrite existing files
php artisan make:tdd Order --force
```

### Advanced Usage

```bash
# Generate tests for a complex module with custom options
php artisan make:tdd BlogPost --all

# Generate only unit tests
php artisan make:tdd Category --model --migration

# Generate only feature tests
php artisan make:tdd Comment --controller --routes
```

## Best Practices

1. **Run Tests First**: Always run the generated tests to ensure they pass with your current setup
2. **Customize as Needed**: Modify the generated tests to match your specific business logic
3. **Use Factories**: Ensure you have model factories set up for better test data generation
4. **Follow TDD**: Use the generated tests as a starting point for your TDD workflow
5. **Keep Tests Updated**: Update tests when you modify your models, controllers, or routes

## Requirements

- PHP ^8.1
- Laravel ^10.0|^11.0
- PHPUnit ^10.0

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

## Support

If you encounter any issues or have questions, please [open an issue](https://github.com/parvej-mahmud/laravel-tdd-generator/issues) on GitHub.