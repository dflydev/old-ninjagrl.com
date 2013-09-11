<?php

namespace Ninjagrl\Domain\Model\Show;

interface ShowRepositoryInterface
{
    function find($identity);
    function add(Show $show);
}
