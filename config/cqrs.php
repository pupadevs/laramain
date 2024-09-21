<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Command Bus
    |--------------------------------------------------------------------------
    |
    | Here you may define the default command bus implementation. 
    | You can specify the class that will be responsible for handling
    | your commands.
    |
    */

    'command_bus' => [
        'class' => \PupaDevs\Laramain\Shared\CommandBus::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Query Bus
    |--------------------------------------------------------------------------
    |
    | Here you may define the default query bus implementation. 
    | You can specify the class that will be responsible for handling
    | your queries.
    |
    */

    'query_bus' => [
        'class' => \PupaDevs\Laramain\Shared\QueryBus::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Event Dispatching
    |--------------------------------------------------------------------------
    |
    | Here you can define whether to enable event dispatching 
    | for your commands and queries. If set to true, the bus will
    | dispatch events upon command or query handling.
    |
    */

    'dispatch_events' => true,

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | Define any middleware you wish to apply to commands and queries.
    | This can be useful for things like logging, validation, etc.
    |
    */

    'middleware' => [
        // Example: \PupaDevs\Laramain\Middleware\LoggingMiddleware::class,
    ],
];
