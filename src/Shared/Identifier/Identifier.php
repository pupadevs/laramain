<?php

declare(strict_types=1);

use Pupadevs\Laramain\Shared\StringValueObject\StringValueObject;
use Ramsey\Uuid\Uuid;

/**
 * Clase Identifier que representa un identificador único basado en UUID.
 * Implementa la interfaz StringValueObject para definir un comportamiento uniforme
 * para objetos que pueden ser convertidos a cadenas de texto.
 */
class Identifier implements StringValueObject
{
    /**
     * Almacena el identificador en formato de cadena.
     * 
     * @var string
     */
    private string $identifier;

    /**
     * Constructor de la clase Identifier.
     * Si no se proporciona un identificador, se genera uno automáticamente usando UUID.
     */
    public function __construct(?string $identifier = null)
    {
        // Si no se proporciona un identificador, generar uno usando UUID v4.
        $this->identifier = $identifier ?? Uuid::uuid4()->toString();
    }

    /**
     * Método para convertir el identificador a cadena de texto.
     * 
     * @return string El identificador como cadena de texto.
     */
    public function toString(): string
    {
        return $this->__toString();
    }

    /**
     * Método mágico que permite tratar la instancia como una cadena de texto.
     * 
     * @return string El identificador como cadena de texto.
     */
    public function __toString(): string
    {
        return $this->identifier;
    }
}
