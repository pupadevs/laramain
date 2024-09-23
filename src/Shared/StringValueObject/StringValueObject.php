<?php

namespace Pupadevs\Laramain\Shared\StringValueObject;

/**
 * Interfaz StringValueObject
 * 
 * Esta interfaz asegura que cualquier clase que la implemente debe ser capaz
 * de ser convertida a una representación de cadena, proporcionando métodos para
 * manejar esta conversión.
 */
interface StringValueObject
{
    /**
     * Convierte el valor del objeto a una cadena de texto.
     * 
     * @return string El valor del objeto como cadena de texto.
     */
    public function toString(): string;

    /**
     * Método mágico que permite convertir el objeto a cadena cuando es tratado como string.
     * 
     * @return string El valor del objeto como cadena de texto.
     */
    public function __toString(): string;
}
