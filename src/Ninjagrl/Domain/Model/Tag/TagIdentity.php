<?php

namespace Ninjagrl\Domain\Model\Tag;

use Ninjagrl\Domain\Shared\ValueObjectInterface;

class TagIdentity implements ValueObjectInterface
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

        if (! $other instanceof TagIdentity) {
            return false;
        }

        return $other->identity() === $this->identity;
    }

    public function copy()
    {
        return new static($this->identity);
    }
}
