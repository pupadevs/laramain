<?php

namespace VendorName\Laramain\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command
{
    protected $signature = 'laramain:install {name}';
    protected $description = 'Install CQRS with DDD folder structure and Command/Query Buses for the specified entity';
    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    public function handle()
    {
        $name = $this->argument('name');
        $srcPath = base_path('src');
        
        // Verificar si la carpeta src existe
        if (!$this->filesystem->exists($srcPath)) {
            $this->info("Creating 'src' directory...");
            $this->filesystem->makeDirectory($srcPath, 0755, true);
        }

        // Crear la estructura de DDD
        $this->createFolderStructure([
            'Domain' => ['Entity', 'ValueObject', 'DomainEvent', 'Interfaces', 'Services'],
            'App' => ['Commands', 'Queries', 'Services'],
            'Infrastructure' => ['Repository', 'Controllers'],
        ], $name);

        // Crear CommandBus y QueryBus
        $this->createBuses();

        $this->info('Laramain package installed successfully with CQRS and DDD structure!');
    }

    protected function createFolderStructure(array $structure, $entityName)
    {
        foreach ($structure as $parent => $folders) {
            $basePath = base_path("src/{$parent}/{$entityName}");
            foreach ($folders as $folder) {
                $path = "{$basePath}/{$folder}";
                if (!$this->filesystem->exists($path)) {
                    $this->filesystem->makeDirectory($path, 0755, true);
                    $this->info("Created: {$path}");
                }
            }
        }
    }

    protected function createBuses()
    {
        $sharedPath = base_path("src/Shared");

        // Verificar si la carpeta Shared existe
        if (!$this->filesystem->exists($sharedPath)) {
            $this->filesystem->makeDirectory($sharedPath, 0755, true);
            $this->info("Created: {$sharedPath}");
        }

        // Crear CommandBus.php si no existe
        if (!$this->filesystem->exists("{$sharedPath}/CommandBus.php")) {
            $this->filesystem->put("{$sharedPath}/CommandBus.php", file_get_contents(__DIR__.'/../Shared/CommandBus.php'));
            $this->info("Created: CommandBus.php");
        }

        // Crear QueryBus.php si no existe
        if (!$this->filesystem->exists("{$sharedPath}/QueryBus.php")) {
            $this->filesystem->put("{$sharedPath}/QueryBus.php", file_get_contents(__DIR__.'/../Shared/QueryBus.php'));
            $this->info("Created: QueryBus.php");
        }
    }
}
