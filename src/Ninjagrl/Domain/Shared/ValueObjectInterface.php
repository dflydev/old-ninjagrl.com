<?php

namespace Ninjagrl\Domain\Shared;

interface ValueObjectInterface
{
    public function sameValueAs($other);
    public function copy();
}
