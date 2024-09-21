<?php

namespace VendorName\Laramain\Shared;

class QueryBus
{
    protected $handlers = [];

    public function registerHandler($query, $handler)
    {
        $this->handlers[get_class($query)] = $handler;
    }

    public function handle($query)
    {
        $queryClass = get_class($query);
        if (!isset($this->handlers[$queryClass])) {
            throw new \Exception("No handler registered for query: {$queryClass}");
        }

        return $this->handlers[$queryClass]->handle($query);
    }
}
