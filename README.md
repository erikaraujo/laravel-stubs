# Opinionated Laravel stubs with resources for API and InertiaJs

Main stub changes:

- controllers don't extend a base `Controller`;
- depending on the parameters passed, this also adds resources for controllers;
- migrations don't have a `down` function;
- model has `with` and `fillable` by default;
- docblocks have been removed;

## Installation

Install the package via composer:

```bash
composer require erikaraujo/laravel-stubs --dev
```

If you want to keep your stubs up to date with every update, add this composer hook to your composer.json file:

```json
"scripts": {
    "post-update-cmd": [
        "@php artisan erikaraujo-stub:publish --force"
    ]
}
```

*note that this has a `force` parameter, which will make the new stubs overwrite the existing ones on the stubs folder.

## Usage

Publish the stubs using this `artisan` command:

```bash
php artisan erikaraujo-stub:publish
```

### Options

1. `--force`
```bash
php artisan erikaraujo-stub:publish --softdeletes
```

Unless you use `--force`, none of the existing stubs inside the `./stubs` folder will be replaced.

2. `--softdeletes`
```bash
php artisan erikaraujo-stub:publish --softdeletes
```

This will automatically add the `SoftDeletes` trait to your model stubs, add `$table->softdeletes()` to your migration stubs and add `forceDelete()` and `restore()` methods to the controllers.

3. `--inertia`
```bash
php artisan erikaraujo-stub:publish --inertia
```

This will import `Inertia\Inertia` to all your non-api controller stubs by default as well as add resources to the controllers methods (if a model is provided).

Resource example:

```php
public function index()
{
    return Inertia::render('{{ model }}/Index', [
        '{{ modelVariable }}' => {{ model }}::paginate()->onEachSide(1),
    ]);
}
```

4. `--json`
```bash
php artisan erikaraujo-stub:publish --json
```

This will add resources to all your api controllers and return a json response with the correct HTTP response code.

Resource example:

```php
public function index()
{
    ${{ modelVariable }} = {{ model }}::all();

    return response()->json([
        'data' => ${{ modelVariable }},
        'total' => ${{ modelVariable }}->count(),
    ], 200);
}
```

5. Multiple
You can mix and match these options and everything will be applied correctly. All examples below are fine:

```bash
php artisan erikaraujo-stub:publish --inertia --softdeletes
```
```bash
php artisan erikaraujo-stub:publish --json --softdeletes
```
```bash
php artisan erikaraujo-stub:publish --json --inertia --softdeletes
```
```bash
php artisan erikaraujo-stub:publish --json --inertia --softdeletes --force
```


## Testing

TODO: Add testing to cover all possible mix and match scenarios.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

For now, just create a PR and I'll take a look.
