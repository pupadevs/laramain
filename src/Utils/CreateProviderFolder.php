<?php 

namespace Pupadevs\Laramain\Utils;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CreateProviderFolder
{
    /**
     * Crea la carpeta de providers y los archivos necesarios.
     *
     * @param Filesystem $filesystem Instancia del sistema de archivos de Laravel.
     * @param Command $command Instancia del comando para imprimir mensajes en la consola.
     * @return void
     */
    public function createProvider(Filesystem $filesystem, Command $command)
    {
        // Define la ruta para la carpeta de Providers.
        $path = base_path('src/Shared/Providers');

        // Verifica si la carpeta 'Providers' ya existe.
        if (!$filesystem->exists($path)) {
            // Si no existe, la crea con permisos 0755.
            $filesystem->makeDirectory($path, 0755, true);
            $command->info("Created Providers Folder");
        }

        // Define la ruta para el archivo EventProvider.php.
        $providerPath = "{$path}/EventProvider.php";
        // Verifica si el archivo 'EventProvider.php' ya existe.
        if (!$filesystem->exists($providerPath)) {
            // Si no existe, carga su contenido desde la ubicación base.
            $content = file_get_contents(__DIR__.'/../Shared/Providers/EventProvider.php');
            // Reemplaza el namespace en el contenido del archivo.
            $content = str_replace('namespace Pupadevs\Laramain\Shared\Providers;', 'namespace Source\Shared\Providers;', $content);
            // Guarda el contenido modificado en la nueva ubicación.
            $filesystem->put($providerPath, $content);
            $command->info("Created: Create EventProvider.php");
        } else {
            // Si el archivo ya existe, informa al usuario.
            $command->line("EventProvider.php already exists.");
        } 

        // Define la ruta para el archivo DependencyProvider.php.
        $dependecyPath = "{$path}/DependencyProvider.php";
        // Verifica si el archivo 'DependencyProvider.php' ya existe.
        if (!$filesystem->exists($dependecyPath)) {
            // Si no existe, carga su contenido desde la ubicación base.
            $content = file_get_contents(__DIR__.'/../Shared/Providers/DependencyProvider.php');
            // Reemplaza el namespace en el contenido del archivo.
            $content = str_replace('namespace Pupadevs\Laramain\Shared\Providers;', 'namespace Source\Shared\Providers;', $content);
            // Guarda el contenido modificado en la nueva ubicación.
            $filesystem->put($dependecyPath, $content);
            $command->info("Created: Create DependencyProvider.php");
        } else {
            // Si el archivo ya existe, informa al usuario.
            $command->line("DependencyProvider.php already exists.");
        }
    }
}
