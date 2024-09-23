<?php

namespace Pupadevs\Laramain\Providers;

use Illuminate\Support\ServiceProvider;
use Pupadevs\Laramain\Console\InstallBasicCommand;
use Pupadevs\Laramain\Console\InstallCommand;

/**
 * Service Provider del paquete, que gestiona la configuración y los comandos del paquete.
 */
class PackageServiceProvider extends ServiceProvider
{
    /**
     * Registra los servicios o configuraciones necesarias para el paquete.
     * Aquí se fusiona el archivo de configuración 'cqrs.php' con las configuraciones de la aplicación.
     * 
     * @return void
     */
    public function register()
    {
        // Fusiona la configuración proporcionada por el paquete con la configuración existente de la aplicación.
        $this->mergeConfigFrom(__DIR__.'/../../config/cqrs.php', 'cqrs');
    }

    /**
     * Método que se ejecuta cuando se carga el paquete. 
     * Aquí se registran los comandos de consola y se publican los archivos de configuración.
     * 
     * @return void
     */
    public function boot()
    {
        // Comprueba si la aplicación está en modo consola para registrar comandos y realizar publicaciones.
        if ($this->app->runningInConsole()) {
            // Registra los comandos disponibles del paquete para que puedan ser ejecutados desde la consola.
            $this->commands([
                InstallCommand::class, 
                InstallBasicCommand::class
            ]);

            // Publica el archivo de configuración 'cqrs.php' para que los usuarios puedan personalizarlo.
            $this->publishes([
                __DIR__.'/../../config/cqrs.php' => config_path('cqrs.php'),
            ], 'config');
        }
    }
}
