<?php 

declare(strict_types=1);

use Pupadevs\Laramain\Shared\StringValueObject\StringValueObject;
use Ramsey\Uuid\Uuid;

class Identifier implements StringValueObject
{
    /**
     * Identifier constructor.
     * @param string|null $identifier
     * 
     */
    public function __construct(private string $identifier)
    {
        if ($identifier === null) {
            $uuid = Uuid::uuid4();
            $this->identifier = $uuid->toString();
        } else {
            $this->identifier = $identifier;
        }
    }

    /**
     * Method to convert identifier to string
     * @return string
     */
    public function toString(): string
    {
        return (string)$this;
    }

    public function __toString(): string
    {
        return $this->identifier;
    }
}