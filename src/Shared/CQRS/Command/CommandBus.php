<?php

declare(strict_types=1);

namespace Pupadevs\Laramain\Shared\CQRS\Command;
use Illuminate\Contracts\Container\Container;
use Pupadevs\Laramain\Shared\CQRS\Command\Command;

/**
 * Clase CommandBus que se encarga de gestionar la ejecución de comandos 
 * mediante la resolución automática de sus manejadores.
 */
class CommandBus
{
    /**
     * Instancia del contenedor de dependencias (IoC container).
     * 
     * @var Container
     */
    protected Container $container;

    /**
     * Constructor del CommandBus.
     * 
     * @param Container $container El contenedor de dependencias que gestiona la inyección de clases.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Ejecuta el comando proporcionado al resolver su manejador.
     * 
     * @param Command $command El comando que debe ser ejecutado.
     * @return mixed El resultado de la ejecución del comando.
     */
    public function execute(Command $command)
    {
        // Resolver la clase del manejador correspondiente al comando.
        $handlerClass = $this->resolveHandlerClass($command);
        // Obtener una instancia del manejador desde el contenedor de dependencias.
        $handler = $this->container->make($handlerClass);

        // Ejecutar el manejador con el comando.
        return $handler->execute($command);
    }

    /**
     * Resuelve la clase del manejador basada en el comando proporcionado.
     * 
     * Se asume que el manejador del comando sigue la convención de 
     * agregar 'Handler' al nombre de la clase del comando.
     * 
     * @param Command $command El comando para el que se debe resolver el manejador.
     * @return string El nombre completo de la clase del manejador.
     */
    protected function resolveHandlerClass(Command $command)
    {
        // Retorna el nombre completo de la clase del manejador correspondiente.
        return get_class($command).'Handler';
    }
}
