<?php

declare(strict_types=1);

namespace Pupadevs\Laramain\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Pupadevs\Laramain\Utils\CreateProviderFolder;
use Pupadevs\Laramain\Utils\UpdateComposer;

/**
 * Comando para instalar una estructura de carpetas DDD (Diseño Impulsado por el Dominio)
 * y los buses de comandos y consultas (CQRS) para una entidad especificada.
 */
class InstallCommand extends Command
{
    /**
     * Firma del comando que se ejecuta en la terminal. 
     * 'laramain:install {name}' donde 'name' es el nombre de la entidad.
     */
    protected $signature = 'laramain:install {name}';

    /**
     * Descripción que aparece en la terminal al usar el comando --help.
     */
    protected $description = 'Install CQRS with DDD folder structure and Command/Query Buses for the specified entity';

    /**
     * Constructor que inicializa las dependencias necesarias para el comando.
     * 
     * @param Filesystem $filesystem  Acceso al sistema de archivos.
     * @param CreateProviderFolder $createProvider  Servicio para crear el folder Provider.
     * @param UpdateComposer $updateComposer  Servicio para actualizar el archivo composer.json.
     */
    public function __construct(
        protected Filesystem $filesystem, 
        private CreateProviderFolder $createProvider,
        private UpdateComposer $updateComposer
    ) {
        parent::__construct();
    }

    /**
     * Método principal que se ejecuta al correr el comando.
     * Este método configura la estructura de carpetas basada en DDD y realiza
     * acciones adicionales como la actualización del archivo composer.json.
     */
    public function handle(): void
    {
        // Estructura de carpetas que sigue el enfoque DDD.
        $folders = [
            'Domain' => ['Entities', 'ValueObjects', 'DomainEvents', 'Interfaces', 'DomainServices'],
            'App' => ['DTOs', 'Commands', 'Queries', 'Services'],
            'Infrastructure' => ['Http' => ['Controllers', 'Middlewares', 'Requests'],'Listerners', 'Jobs', 'Repositories'],
        ];

        // Obtiene el nombre de la entidad desde el argumento pasado al comando.
        $name = $this->argument('name');

        // Define la ruta base donde se creará la estructura ('src').
        $srcPath = base_path("src");

        // Verifica si la carpeta 'src' ya existe, de lo contrario, la crea.
        if (!$this->filesystem->exists($srcPath)) {
            $this->info("Creating 'src' directory...");
        } else {
            $this->info("'src' directory already exists.");
        }

        // Crea la estructura de carpetas DDD para la entidad especificada.
        $this->createFolderStructure($folders, $name);

        // Crea los providers en la carpeta correspondiente.
        $this->createProvider->createProvider($this->filesystem, $this);

        // Actualiza el archivo composer.json para reflejar el autoload del namespace.
        $this->updateComposer->updateComposerAutoload($this->filesystem, $this);

        // Mensaje de confirmación tras la instalación exitosa.
        $this->info('Laramain package installed successfully with CQRS and DDD structure!');
    }

    /**
     * Crea la estructura de carpetas de acuerdo con el enfoque DDD.
     * 
     * @param array $structure  Arreglo que define la estructura de carpetas a crear.
     * @param string $entityName  Nombre de la entidad para la cual se está creando la estructura.
     */
    protected function createFolderStructure(array $structure, $name): void
    {
        // Itera sobre la estructura definida y crea las carpetas.
        foreach ($structure as $parent => $folders) {
            // Define la ruta base para las carpetas.
            $basePath = base_path("src/{$name}/{$parent}");
    
            // Verifica si la carpeta ya existe, de lo contrario, la crea.
            if (!$this->filesystem->exists($basePath)) {
                $this->filesystem->makeDirectory($basePath, 0755, true);
                $this->info("Created: {$basePath}");
            } else {
                $this->line("{$parent} already exists.");
            }
    
            // Recorre las subcarpetas y las crea si no existen.
            foreach ($folders as $key => $folder) {
                if (is_array($folder)) {
                    // Llamada recursiva para crear subcarpetas.
                    $this->createFolderStructure([$key => $folder], "{$name}/{$parent}");
                } else {
                    // Crea la carpeta individual.
                    $path = "{$basePath}/{$folder}";
    
                    if (!$this->filesystem->exists($path)) {
                        $this->filesystem->makeDirectory($path, 0755, true);
                        $this->info("Created: {$path}");
                    } else {
                        $this->line("{$folder} already exists.");
                    }
                }
            }
        }
    }

   
}
