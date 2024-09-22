<?php

namespace Pupadevs\Laramain\Shared\CQRS\Command;

use Illuminate\Contracts\Container\Container;


class CommandBus
{
    protected $handlers = [];

    public function __construct(protected Container $container)
    {
    }

  

    public function handle($command)
    {
        $commandClass = $this->resolveHandlerClass($command);
        if(!$commandClass) {
            throw new \Exception('No handler registered for command: '.get_class($command));
        }
        $handler = $this->container->make($commandClass);

        return $this->handlers[$commandClass]->handle($command);
    }

    protected function resolveHandlerClass(Command $command)
    {
  
        return get_class($command).'Handler';
    }
}
