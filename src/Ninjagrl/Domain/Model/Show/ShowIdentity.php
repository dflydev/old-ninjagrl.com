<?php

namespace Ninjagrl\Domain\Model\Show;

use Ninjagrl\Domain\Shared\ValueObjectInterface;

class ShowIdentity implements ValueObjectInterface
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

    public function sameValueAs($other = null)
    {
        if (null === $other) {
            return false;
        }

        if ($this === $other) {
            return true;
        }

        if (! $other instanceof ShowIdentity) {
            return false;
        }

        return $other->identity() === $this->identity;
    }

    public function copy()
    {
        return new static($this->identity);
    }
}
