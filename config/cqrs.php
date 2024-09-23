<?php
return [

/*
|--------------------------------------------------------------------------
| Command Bus (Bus de Comandos)
|--------------------------------------------------------------------------
|
| Aquí puedes definir la implementación del bus de comandos predeterminada. 
| Puedes especificar la clase que será responsable de manejar tus comandos.
|
*/

'command_bus' => [
    'class' => \PupaDevs\Laramain\Shared\CQRS\Command\CommandBus::class,
],

/*
|--------------------------------------------------------------------------
| Query Bus (Bus de Consultas)
|--------------------------------------------------------------------------
|
| Aquí puedes definir la implementación del bus de consultas predeterminada. 
| Puedes especificar la clase que será responsable de manejar tus consultas.
|
*/

'query_bus' => [
    'class' => \PupaDevs\Laramain\Shared\CQRS\Query\QueryBus::class,
],

/*
|--------------------------------------------------------------------------
| Despacho de Eventos
|--------------------------------------------------------------------------
|
| Aquí puedes definir si deseas habilitar el despacho de eventos 
| para tus comandos y consultas. Si se establece en true, el bus
| despachará eventos al manejar un comando o consulta.
|
*/

'dispatch_events' => true,

];
