<?php

namespace Ninjagrl\Domain\Shared;

interface EntityInterface
{
    public function identity();
    public function sameIdentityAs($other = null);
}
