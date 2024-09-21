<?php 
namespace Pupadevs\Laramain\Shared\StringValueObject;

interface StringValueObject
{
    public function toString(): string;

    public function __toString(): string;
}