<?php

namespace ErikAraujo\Stubs;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

class StubsPublishCommand extends Command
{
    protected $signature = 'erikaraujo-stub:publish {--force : Overwrite any existing files} {{--inertia : Copy inertia controller stubs}} {{--json : Copy json controller stubs}} {{--softdeletes : Add destroy and restore to controller, the trait to the model and the timestamp to the migrations}}';

    protected $description = 'Publish all opinionated stubs that are available for customization';

    use ConfirmableTrait;

    public function handle()
    {
        $fileSystem = new Filesystem;

        if (! $this->confirmToProceed()) {
            return 1;
        }
        
        if (! is_dir($stubsPath = $this->laravel->basePath('stubs'))) {
            $fileSystem->makeDirectory($stubsPath);
        }

        $stubsBasePath = __DIR__ . '/../stubs';

        // 1. Create Temp folder;
        $tempPath = $stubsBasePath . '/temp';
        $fileSystem->makeDirectory($tempPath);

        // 2. Copy everything from /../stubs/default to Temp folder;
        $this->copyFiles($stubsBasePath . '/default', $tempPath, true);

        // 3. If $this->option('softdeletes'), force copy everything from /../stubs/specials/softdeletes to Temp folder;
        if ($this->option('softdeletes')) {
            $this->copyFiles($stubsBasePath . '/specials/softdeletes', $tempPath, true);
        }

        // 4. If $this->option('inertia'), force copy everything from /../stubs/specials/inertia to Temp folder;
        if ($this->option('inertia')) {
            $this->copyFiles($stubsBasePath . '/specials/inertia', $tempPath, true);
        }

        // 5. If $this->option('softdeletes') and $this->option('inertia'), force copy everything from /../stubs/specials/inertia-softdeletes to Temp folder;
        if ($this->option('softdeletes') && $this->option('inertia')) {
            $this->copyFiles($stubsBasePath . '/specials/inertia-softdeletes', $tempPath, true);
        }

        // 6. If $this->option('json'), force copy everything from /../stubs/specials/json to Temp folder;
        if ($this->option('json')) {
            $this->copyFiles($stubsBasePath . '/specials/json', $tempPath, true);
        }

        // 7. If $this->option('softdeletes') and $this->option('json'), force copy everything from /../stubs/specials/json-softdeletes to Temp folder;
        if ($this->option('softdeletes') && $this->option('json')) {
            $this->copyFiles($stubsBasePath . '/specials/json-softdeletes', $tempPath, true);
        }

        // 8. Copy everything from the Temp folder to the app's stubs folder ($stubsPath)
        $this->copyFiles($tempPath, $stubsPath, $this->option('force'));

        // 9. Delete Temp folder
        $fileSystem->deleteDirectory($tempPath);

        $this->info('All done!');
    }

    private function copyFiles(string $origin, string $target, bool $force = false) {
        collect(File::files($origin))->each(function (SplFileInfo $file) use ($target, $force) {
            $sourcePath = $file->getPathname();

            $targetPath = $target . "/{$file->getFilename()}";

            if (! file_exists($targetPath) || $force) {
                file_put_contents($targetPath, file_get_contents($sourcePath));
            }
        });
    }
}
