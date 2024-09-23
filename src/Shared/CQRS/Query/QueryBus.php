<?php

namespace Pupadevs\Laramain\Shared\CQRS\Query;

use Illuminate\Container\Container;

/**
 * Clase QueryBus encargada de gestionar la ejecución de consultas (queries) 
 * mediante la resolución automática de sus manejadores.
 */
class QueryBus
{
    /**
     * Instancia del contenedor de dependencias (IoC container).
     * 
     * @var Container
     */
    protected Container $container;

    /**
     * Constructor del QueryBus.
     * 
     * @param Container $container El contenedor de dependencias que gestiona la inyección de clases.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Maneja la ejecución de una consulta (query) proporcionando el manejador correspondiente.
     * 
     * @param Query $query La consulta que debe ser ejecutada.
     * @return mixed El resultado de la consulta ejecutada.
     * @throws \Exception Si no se encuentra un manejador registrado para la consulta.
     */
    public function handle(Query $query): mixed
    {
        // Resolver la clase del manejador correspondiente a la consulta.
        $handlerClass = $this->resolveHandlerClass($query);
        // Obtener una instancia del manejador desde el contenedor de dependencias.
        $handler = $this->container->make($handlerClass);

        // Lanzar una excepción si no hay un manejador registrado para la consulta.
        if (!$handler) {
            throw new \Exception('No handler registered for query: ' . get_class($query));
        }

        // Ejecutar el manejador con la consulta.
        return $handler->handle($query);
    }

    /**
     * Resuelve la clase del manejador basada en la consulta proporcionada.
     * 
     * Se asume que el manejador de la consulta sigue la convención de 
     * agregar 'Handler' al nombre de la clase de la consulta.
     * 
     * @param Query $query La consulta para la que se debe resolver el manejador.
     * @return string El nombre completo de la clase del manejador.
     */
    protected function resolveHandlerClass(Query $query)
    {
        // Retorna el nombre completo de la clase del manejador correspondiente.
        return get_class($query) . 'Handler';
    }
}
