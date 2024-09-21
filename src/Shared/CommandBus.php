<?php

namespace Pupadevs\Laramain\Shared;

class CommandBus
{
    protected $handlers = [];

    public function registerHandler($command, $handler)
    {
        $this->handlers[get_class($command)] = $handler;
    }

    public function handle($command)
    {
        $commandClass = get_class($command);
        if (!isset($this->handlers[$commandClass])) {
            throw new \Exception("No handler registered for command: {$commandClass}");
        }

        return $this->handlers[$commandClass]->handle($command);
    }
}
