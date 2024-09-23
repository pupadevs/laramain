# Pupadevs\Laramain

[![Packagist Version](https://img.shields.io/packagist/v/pupadevs/laramain.svg?style=flat-square)](https://packagist.org/packages/pupadevs/laramain)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/pupadevs/laramain.svg?style=flat-square)](https://packagist.org/packages/pupadevs/laramain)
[![PHP Version](https://img.shields.io/badge/PHP-%5E8.2-8892BF.svg?style=flat-square)](https://www.php.net/)

## Descripción
**Pupadevs\Laramain** Es un paquete Laravel diseñado para facilitar la implementación de la arquitectura DDD (Domain-Driven Design) y CQRS (Command Query Responsibility Segregation) en tus aplicaciones Laravel.
## Requisitos
- PHP >= 8.2
- Laravel >= 10x
## Instalación
Para instalar el paquete es necesario tener instalado Composer.
Ejecuta el siguiente comando
```bash
composer require pupadevs/laramain
```
## Uso
 **1.Comando Artisan con parametro**
  ```bash
  php artisan laramain:install {nombreDominio}
  ```
```bash
php artisan vendor:publish --provider="Pupadevs\Laramain\Providers\PackageServiceProvider"
```
**2.Comando Artisan basico**
```bash
php artisan laramain:install-basic
```
**Repetimos publicacion del vendor**

### Uso del Command y Query Bus

Para utilizar las clases `CommandBus` y `QueryBus`, asegúrate de incluir los siguientes namespaces en tu archivo:

```php
use Pupadevs\Laramain\Shared\CQRS\Command\CommandBus;
use Pupadevs\Laramain\Shared\CQRS\Query\QueryBus;
```
Luego, puedes instanciarlas en tu clase como se muestra a continuación:
```php
class YourService
{
    protected CommandBus $command;
    protected QueryBus $query;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus)
    {
        $this->command = $commandBus;
        $this->query = $queryBus;
    }

    public function someMethod($email, $user, $ip)
    {
        $this->query->handle(new SomeQuery(new ValueObject($value)));
        $this->command->execute(new SomeCommand($value));
    }
}
```

### Uso de la Interfaz StringValueObject y la Clase Identifier

### Interfaz StringValueObject

La interfaz `StringValueObject` asegura que cualquier clase que la implemente debe ser capaz de convertirse a una representación de cadena, proporcionando métodos para manejar esta conversión.

#### Métodos de la Interfaz

```php
interface StringValueObject
{
    public function toString(): string;

    public function __toString(): string;
}
```
### Clase Identifier
La clase Identifier implementa la interfaz StringValueObject y representa un identificador único basado en UUID.
**Instanciación**
Para usar la clase Identifier, primero incluye el namespace correspondiente:
```php

use Pupadevs\Laramain\Shared\YourNamespace\Identifier; 
```
Luego, puedes crear una instancia de la clase Identifier. Si no proporcionas un identificador, se generará uno automáticamente:
```php
// Genera un nuevo identificador único (UUID)
$identifier = new Identifier();
```
**Conversión a Cadena**
Para convertir el identificador a cadena de texto, puedes usar el método toString() o simplemente tratar la instancia como una cadena:
```php
// Usando el método toString()
echo $identifier->toString(); // Imprime el UUID generado

```

## Version 2.0.0

### Nuevas funcionalidades
- Nueva funcion intalacion basica
- Se agregó la carpeta DTOs a la capa de App a la instalacion con parametros.
- Se agregó la carpeta Middlewares y Requests a la capa de Infrastructure a la instalacion con parametros.
- Se agregan EventProvider y DependencyProvider a la carpeta Shared para manejar evento y DIP


## Licencia
Este proyecto está bajo la Licencia MIT.

## Colaboración

Si deseas contribuir a **Pupadevs\Laramain**, por favor sigue estos pasos:

1. **Fork del repositorio**: Crea un fork del repositorio en GitHub.
2. **Crea una nueva rama**: Realiza tus cambios en una nueva rama utilizando el siguiente comando:
   ```bash
   git checkout -b nombre-de-la-rama