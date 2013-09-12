<?php

namespace Ninjagrl\Domain\Shared;

interface ValueObjectInterface
{
    public function sameValueAs($other = null);
    public function copy();
}
