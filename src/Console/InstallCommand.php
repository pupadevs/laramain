<?php
declare(strict_types=1);
namespace Pupadevs\Laramain\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

/**
 * Este comando se utiliza para instalar la estructura de carpetas DDD 
 * (Diseño impulsado por el Dominio) y los buses de comandos y consultas (CQRS) 
 * para una entidad especificada en el comando.
 */

class InstallCommand extends Command
{
    /**
     * Define la firma del comando 'laramain:install {name}', donde 'name' 
     * es el nombre de la entidad que el usuario quiere crear.
     */
 
    protected $signature = 'laaramain:install {name}';
    /**
     * Descripción del comando para indicar lo que hace.
     */
    protected $description = 'Install CQRS with DDD folder structure and Command/Query Buses for the specified entity';

    /**
     * Constructor que inicializa el comando con el sistema de archivos.
     *
     * @param Filesystem $filesystem
     */
    
    public function __construct(protected Filesystem $filesystem)
    {
        parent::__construct();
    }

    /**
     * Método principal que se ejecuta al correr el comando.
     * @return void
     */
    public function handle(): void
    {
        $folders = [
            'Domain' => ['Entity', 'ValueObject', 'DomainEvent', 'Interfaces', 'DomainServices'],
            'App' => ['Commands', 'Queries', 'Services'],
            'Infrastructure' => ['Repository', 'Controllers','Listerners'],
        ];
        // Se obtiene el nombre de la entidad desde los argumentos del comando.
        $name = $this->argument('name');
        $srcPath = base_path("src");
        
        // Verificar si la carpeta 'src' ya existe. Si no, se crea.
        if (!$this->filesystem->exists($srcPath)) {
            $this->info("Creating 'src' directory...");
            $this->filesystem->makeDirectory($srcPath, 0755, true);
        }


        // Crear la estructura de carpetas basada en DDD (Diseño impulsado por el Dominio).
        $this->createFolderStructure($folders, $name);

        // Crear CommandBus y QueryBus.
        $this->createBuses();
        // Crear StringValueObject
        $this->createStringValueObject();
        // Crear Identifier
        $this->creatIdentifier();
        $this->updateComposerAutoload($name);

        $this->info('Laramain package installed successfully with CQRS and DDD structure!');
    }

    /**
     * Crea la estructura de carpetas de DDD para la entidad especificada.
     *
     * @param array $structure Estructura de carpetas a crear.
     * @param string $entityName Nombre de la entidad para la que se crea la estructura.
     * @return void
     */
    protected function createFolderStructure(array $structure, $name): void
    {
        foreach ($structure as $parent => $folders) {
            $basePath = base_path("src/{$parent}/{$name}");
            foreach ($folders as $folder) {
                $path = "{$basePath}/{$folder}";
                // Si el directorio no existe, lo crea.
                // Si el directorio no existe, lo crea.
                if (!$this->filesystem->exists($path)) {
                    $this->filesystem->makeDirectory($path, 0755, true);
                    $this->info("Created: {$path}");
                }
            }
        }
    }

    /**
     * Crea los buses de comandos (CommandBus) y consultas (QueryBus).
     * @return void
     */
    protected function createBuses(): void
    {
        $sharedPath = base_path("src/Shared/CQRS");
    
        // Verificar si la carpeta Shared existe, si no, se crea.
        if (!$this->filesystem->exists($sharedPath)) {
            $this->filesystem->makeDirectory($sharedPath, 0755, true);
            $this->info("Created: {$sharedPath}");
        }
    
        // Crear la carpeta Command si no existe.
        $commandPath = "{$sharedPath}/Command";
        if (!$this->filesystem->exists($commandPath)) {
            $this->filesystem->makeDirectory($commandPath, 0755, true);
            $this->info("Created: {$commandPath}");
        }
    
        // Crear CommandBus.php si no existe.
        $commandBusPath = "{$commandPath}/CommandBus.php";
        if (!$this->filesystem->exists($commandBusPath)) {
            $this->filesystem->put($commandBusPath, file_get_contents(__DIR__.'/../Shared/CQRS/Command/CommandBus.php'));
            $this->info("Created: CommandBus.php");
        } else {
            $this->info("CommandBus.php already exists.");
        } 
    
        // Crear Command.php si no existe.
        $commandInterfacePath = "{$commandPath}/Command.php";
        if (!$this->filesystem->exists($commandInterfacePath)) {
            $this->filesystem->put($commandInterfacePath, file_get_contents(__DIR__.'/../Shared/CQRS/Command/Command.php'));
            $this->info("Created: Command.php");
        } else {
            $this->info("Command.php already exists.");
        }
    
        // Crear la carpeta Query si no existe.
        $queryPath = "{$sharedPath}/Query";
        if (!$this->filesystem->exists($queryPath)) {
            $this->filesystem->makeDirectory($queryPath, 0755, true);
            $this->info("Created: {$queryPath}");
        }
    
        // Crear QueryBus.php si no existe.
        $queryBusPath = "{$queryPath}/QueryBus.php";
        if (!$this->filesystem->exists($queryBusPath)) {
            $this->filesystem->put($queryBusPath, file_get_contents(__DIR__.'/../Shared/CQRS/Query/QueryBus.php'));
            $this->info("Created: QueryBus.php");
        } else {
            $this->info("QueryBus.php already exists.");
        }
    
        // Crear Query.php si no existe.
        $queryInterfacePath = "{$queryPath}/Query.php";
        if (!$this->filesystem->exists($queryInterfacePath)) {
            $this->filesystem->put($queryInterfacePath, file_get_contents(__DIR__.'/../Shared/CQRS/Query/Query.php'));
            $this->info("Created: Query.php");
        } else {
            $this->info("Query.php already exists.");
        }
    }
    

    /**
     * Crea un StringValueObject en la carpeta Shared/ValueObject si no existe.
     */
    protected function createStringValueObject()
    {
        $sharePath = base_path('src/Shared/StringValueObject');

        // Verificar si la carpeta Shared existe, si no, se crea.
        if (!$this->filesystem->exists($sharePath)) {
            $this->filesystem->makeDirectory($sharePath, 0755, true);
            $this->info("Created: {$sharePath}");
        } else {
            $this->info("{$sharePath} already exists.");
        }

        // Crear StringValueObject.php si no existe.
        if (!$this->filesystem->exists("{$sharePath}/StringValueObject.php")) {
            $this->filesystem->put("{$sharePath}/StringValueObject.php", file_get_contents(__DIR__.'/../Shared/StringValueObject/StringValueObject.php'));
            $this->info("Created: StringValueObject.php");
        } else {
            $this->info("StringValueObject.php already exists.");
        }
    }

    /**
     * Crea un Identificador en la carpeta Shared/Identifier si no existe.
     */
    protected function creatIdentifier()
    {
        $identifierPath = base_path('src/Shared/Identifier');
        if (!$this->filesystem->exists($identifierPath)) {
            $this->filesystem->makeDirectory($identifierPath, 0755, true);
            $this->info("Created: {$identifierPath}");
        } else {
            $this->info("{$identifierPath} already exists.");
        }

        // Crear Identifier.php si no existe.
        if (!$this->filesystem->exists("{$identifierPath}/Identifier.php")) {
            $this->filesystem->put("{$identifierPath}/Identifier.php", file_get_contents(__DIR__.'/../Shared/Identifier/Identifier.php'));
            $this->info("Created: Identifier.php");
        } else {
            $this->info("Identifier.php already exists.");
        }
    }
    protected function updateComposerAutoload()
    {
        $composerJsonPath = base_path('composer.json');
    
        if (!$this->filesystem->exists($composerJsonPath)) {
            $this->error('composer.json not found!');
            return;
        }
    
        // Cargar el archivo composer.json
        $composerJson = json_decode($this->filesystem->get($composerJsonPath), true);
    
        // Añadir el namespace genérico para la carpeta src
        $namespace = 'Source\\';
        $srcPath = 'src/';
    
        if (!isset($composerJson['autoload']['psr-4'][$namespace])) {
            $composerJson['autoload']['psr-4'][$namespace] = $srcPath;
    
            // Guardar los cambios en composer.json
            $this->filesystem->put($composerJsonPath, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    
            $this->info("composer.json updated with the new namespace {$namespace}.");
        } else {
            $this->info("Namespace {$namespace} already exists in composer.json.");
        }
    }
    
}
