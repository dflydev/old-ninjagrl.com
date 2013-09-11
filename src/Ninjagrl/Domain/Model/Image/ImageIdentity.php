<?php

namespace Ninjagrl\Domain\Model\Image;

use Ninjagrl\Domain\Shared\ValueObjectInterface;

class ImageIdentity implements ValueObjectInterface
{
    private $identity;

    public function __construct($identity)
    {
        $this->identity = $identity;
    }

    public function identity()
    {
        return $this->identity;
    }

    public function sameValueAs($other)
    {
        if ($this === $other) {
            return true;
        }

        if (! $other instanceof ImageIdentity) {
            return false;
        }

        return $other->identity() === $this->identity;
    }

    public function copy()
    {
        return new static($this->identity);
    }
}
