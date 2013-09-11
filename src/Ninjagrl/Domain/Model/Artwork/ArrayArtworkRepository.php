<?php

namespace Ninjagrl\Domain\Model\Artwork;

class ArrayArtworkRepository implements ArtworkRepositoryInterface
{
    private $artworks;

    public function __construct(array $artworks = null)
    {
        $this->artworks = $artworks;
    }

    function find($identifier)
    {
        foreach ($this->artworks as $artwork) {
            if ($artwork->identifier() === $identifier) {
                return $artwork;
            }
        }

        return null;
    }

    function findAll()
    {
        return $this->artworks;
    }

    function add(Artwork $artwork)
    {
        $this->artworks[] = $artwork;
    }

    public function flush()
    {
        // no-op
    }
}
