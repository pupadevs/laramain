<?php 
namespace Pupadevs\Laramain\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Pupadevs\Laramain\Utils\CreateProviderFolder;
use Pupadevs\Laramain\Utils\UpdateComposer;

class InstallBasicCommand extends Command {

    // Definir el nombre del comando y su firma.
    protected $signature = 'laramain:install-basic';

    /**
     * Descripción del comando, que describe su propósito principal.
     * Este comando instala una estructura de carpetas basada en CQRS y DDD.
     */
    protected $description = 'Instalar estructura CQRS con DDD y Command/Query Buses';

    /**
     * Constructor del comando.
     * Inicializa la clase con el sistema de archivos y las utilidades para crear carpetas de proveedores y actualizar el autoload del composer.
     *
     * @param Filesystem $filesystem Sistema de archivos de Laravel.
     * @param CreateProviderFolder $createProvider Utilidad para crear la carpeta de proveedores.
     * @param UpdateComposer $updateComposer Utilidad para actualizar el archivo composer.json.
     */
    public function __construct(
        protected Filesystem $filesystem, 
        private CreateProviderFolder $createProvider, 
        private UpdateComposer $updateComposer
    ) {
        parent::__construct();
    }

    /**
     * Método principal del comando, que se ejecuta cuando se llama al comando en la terminal.
     * Aquí se crea la estructura de carpetas para un proyecto DDD con CQRS.
     *
     * @return void
     */
    public function handle(): void
    {
        // Definir la estructura de carpetas que se creará en el proyecto.
        $folders = [
            'Domain' => ['Entities', 'ValueObjects', 'DomainEvents', 'Interfaces', 'DomainServices'],
            'App' => ['DTOs', 'Commands', 'Queries', 'Services'],
            'Infrastructure' => ['Controllers', 'Listeners', 'Jobs', 'Repositories'],
        ];

        // Definir la ruta base de la carpeta 'src'.
        $srcPath = base_path("src");

        // Verificar si la carpeta 'src' ya existe, y crearla si no.
        if (!$this->filesystem->exists($srcPath)) {
            $this->info("Creando directorio 'src'...");
            $this->filesystem->makeDirectory($srcPath, 0755, true);
        } else {
            $this->info("El directorio 'src' ya existe.");
        }

        // Crear la estructura de carpetas según el array $folders.
        $this->createFolderStructure($folders);

        // Crear la carpeta para los proveedores (service providers) necesarios.
        $this->createProvider->createProvider($this->filesystem, $this);

        // Actualizar el archivo composer.json para añadir el autoload correcto.
        $this->updateComposer->updateComposerAutoload($this->filesystem, $this);

        // Mostrar un mensaje al final del proceso de instalación.
        $this->info('¡Paquete Laramain instalado con éxito con estructura CQRS y DDD!');
    }

    /**
     * Crear la estructura de carpetas basada en el array de estructura.
     * Esta función itera sobre cada carpeta principal y sus subcarpetas, creando cada una si no existe.
     *
     * @param array $structure Estructura de carpetas a crear.
     * @return void
     */
    protected function createFolderStructure(array $structure): void
    {
        foreach ($structure as $parent => $folders) {
            // Definir la ruta base para la carpeta principal.
            $basePath = base_path("src/{$parent}");

            // Verificar si la carpeta principal existe, y crearla si no.
            if (!$this->filesystem->exists($basePath)) {
                $this->filesystem->makeDirectory($basePath, 0755, true);
                $this->info("Creada: {$basePath}");
                $this->line("Carpeta creada: {$parent}");
            } else {
                $this->line("{$parent} ya existe.");
            }

            // Asegurarse de que $folders es un array antes de proceder.
            if (is_array($folders)) {
                foreach ($folders as $folder) {
                    // Definir la ruta para cada subcarpeta y crearla si no existe.
                    $path = "{$basePath}/{$folder}";

                    if (!$this->filesystem->exists($path)) {
                        $this->filesystem->makeDirectory($path, 0755, true);
                        $this->info("Creada: {$path}");
                    } else {
                        $this->line("{$folder} ya existe.");
                    }
                }
            }
        }
    }
}
