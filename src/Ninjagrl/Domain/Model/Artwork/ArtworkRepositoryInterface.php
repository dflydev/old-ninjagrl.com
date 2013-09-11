<?php

namespace Ninjagrl\Domain\Model\Artwork;

interface ArtworkRepositoryInterface
{
    function find(ArtworkIdentity $artworkIdentity);
    function findAll();
    function add(Artwork $artwork);
    function flush();
}
