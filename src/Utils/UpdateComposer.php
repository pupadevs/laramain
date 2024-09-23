<?php 

namespace Pupadevs\Laramain\Utils;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class UpdateComposer
{
    /**
     * Actualiza el autoload de Composer para incluir un nuevo namespace.
     *
     * @param Filesystem $filesystem Instancia del sistema de archivos de Laravel.
     * @param Command $command Instancia del comando para imprimir mensajes en la consola.
     * @return void
     */
    public function updateComposerAutoload(Filesystem $filesystem, Command $command)
    {
        // Define la ruta del archivo composer.json en la raíz del proyecto.
        $composerJsonPath = base_path('composer.json');

        // Verifica si el archivo composer.json existe.
        if (!$filesystem->exists($composerJsonPath)) {
            // Si no existe, muestra un mensaje de error.
            $command->error('composer.json not found!');
            return; // Termina la ejecución si el archivo no se encuentra.
        }

        // Carga el contenido del archivo composer.json y lo decodifica en un array.
        $composerJson = json_decode($filesystem->get($composerJsonPath), true);

        // Define el namespace genérico y la ruta correspondiente para autoload.
        $namespace = 'Source\\';
        $srcPath = 'src/';

        // Verifica si el namespace ya está definido en composer.json.
        if (!isset($composerJson['autoload']['psr-4'][$namespace])) {
            // Si no está definido, lo añade.
            $composerJson['autoload']['psr-4'][$namespace] = $srcPath;

            // Guarda los cambios en composer.json, con formato legible.
            $filesystem->put($composerJsonPath, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            // Informa al usuario que se ha actualizado composer.json.
            $command->info("composer.json updated with the new namespace {$namespace}.");
        } else {
            // Si el namespace ya existe, informa al usuario.
            $command->line("Namespace {$namespace} already exists in composer.json.");
        }
    }
}
