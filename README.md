# Pupadevs\Laramain
<div align="center">
<img src= "https://github.com/user-attachments/assets/2f4a0a0a-94a9-4566-9907-5931ac9640da" width=250 >
</div>
</br>


[![Packagist Version](https://img.shields.io/packagist/v/pupadevs/laramain.svg?style=flat-square)](https://packagist.org/packages/pupadevs/laramain)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/pupadevs/laramain.svg?style=flat-square)](https://packagist.org/packages/pupadevs/laramain)
[![PHP Version](https://img.shields.io/badge/PHP-%5E8.2-8892BF.svg?style=flat-square)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-%5E10.0%E2%9C%94-FF2D55.svg?style=flat-square)](https://www.laravel.com)


## Descripción
**Pupadevs\Laramain** Es un paquete Laravel diseñado para facilitar la implementación de la arquitectura DDD (Domain-Driven Design) y CQRS (Command Query Responsibility Segregation) en tus aplicaciones Laravel.
## Requisitos
- PHP >= 8.2
- Laravel >= 10x
## Instalación
Para instalar el paquete es necesario tener instalado Composer.
https://getcomposer.org/download/
Ejecuta el siguiente comando
```bash
composer require pupadevs/laramain
```
## Uso de comandos de artisan
 **1.Comando Artisan con parametro**
Este comando te pedirá que ingreses un nombre para tu dominio. Usará ese nombre para crear la estructura de carpetas siguiendo la arquitectura DDD (Domain-Driven Design). La carpeta principal llevará el nombre que hayas proporcionado como dominio, y dentro de ella se generarán las subcarpetas correspondientes para organizar tu proyecto de acuerdo con los principios de DDD.

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

## Uso del Command y Query Bus

Para utilizar las clases `CommandBus` y `QueryBus`, asegúrate de incluir los siguientes **namespaces** en tu archivo:

```php
use Pupadevs\Laramain\Shared\CQRS\Command\CommandBus;
use Pupadevs\Laramain\Shared\CQRS\Query\QueryBus;
```
  ### **Creando Comando personalizado**

   - En la ubicacion **App/Commands**, crearemos nuestros commandos y sus respectivos manejadores
   - Para la creacion de un Comando , necesitamos implementar de la interfaz Command **use Pupadevs\Laramain\Shared\CQRS\Command\CommandBus;**

```php
namespace App\Commands;

class CreateUserCommand implements Command
{
    public string $name;
    public string $email;
    public string $password;

    public function __construct(string $name, string $email, string $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }
}
```
- **Creando Manejador de comando**
   -En la misma ubicación donde hemos creado nuestro Command, también debemos crear su Handler.
¡Importante! El nombre del Handler debe ser exactamente igual al del Command, pero con la palabra **"Handler"** al final. Por ejemplo:

```php
namespace App\Commands;

use App\Commands\CreateUserCommand;
use App\Repositories\UserRepository;

class CreateUserCommandHandler
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(CreateUserCommand $command)
    {
        // Lógica para crear un usuario
        $this->userRepository->create([
            'name' => $command->name,
            'email' => $command->email,
            'password' => bcrypt($command->password),
        ]);
    }
}
```
### **Creando Query personalizada**
 - En la ubicacion **App/Querys**, crearemos nuestros commandos y sus respectivos manejadores
 - Para la creacion de un Comando , necesitamos implementar la interface Query **use Pupadevs\Laramain\Shared\CQRS\Query\QueryBus;**
```php
class CheckEmailQuery implements Query
{
   
    public Email $email;

    public function __construct( Email $email)
    {
      
        $this->email = $email;
     
    }

    public function getEmail(){
        return $this->email;
    }
}

```
- **Creando Manejador de Query**
   -En la misma ubicación donde hemos creado nuestra Query, también debemos crear su Handler.
¡Importante! El nombre del Handler debe ser exactamente igual al de la Query, pero con la palabra **"Handler"** al final. Por ejemplo:

```php
namespace App\Query;

use App\Queyr\CheckEmailQuery;
use App\Repositories\UserRepository;

class CheckEmailQueryHanlder
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(CheckEmailQuery $query)
    {
       return $this->userRepository->findEmail($query->toString());
    }
}
```

### Luego, puedes instanciarlas en tu Servcio o controlador como se muestra a continuación:
**Uso en un servicio de Aplicacion**
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

    public function someMethod($email)
    {
        $this->query->handle(new CheckEmailQuery(new Email($email)));
    }
}
```
**Uso en un Controlador**
```php
Class SomeController{

    protected CommandBus $command;
    protected QueryBus $query;

    public function __construct(CommandBus $command, QueryBus $query)
    {
        $this->command = $commandBus;
        $this->query = $queryBus;
    } 

    public function showSomeThings(Request $request){
        $this->query->handle(new CheckEmailQuery($request->email));
        $this->command->execute(new CreateUserCommand($request->all()));
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
Para convertir el identificador a cadena de texto, puedes usar el método **toString()** o simplemente tratar la instancia como una cadena:
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
