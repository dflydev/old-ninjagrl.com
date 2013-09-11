<?php

namespace Ninjagrl\Domain\Model\Venue;

use Ninjagrl\Domain\Model\Artwork\Artwork;
use Ninjagrl\Domain\Model\Venue\View\VenueView;

class Venue
{
    private $identity;
    private $name;
    private $description;
    private $url;
    private $location;

    public function __construct(
        $identity,
        $name,
        $description = null,
        $url = null,
        $location = null
    ) {
        $this->identity = $identity;
        $this->setBasicInformation($name, $description);
        $this->url = $url;
        $this->location = $location;
    }

    public function setBasicInformation($name, $description = null)
    {
        $this->name = $name;
        $this->description = $description;

        return $this;
    }

    public function setUrl($url = null)
    {
        $this->url = $url;

        return $this;
    }

    public function setLocation(Location $location = null)
    {
        $this->location = $location;

        return $this;
    }

    public function render(VenueView $venueView)
    {
        $venueView->identity = $this->identity;
        $venueView->name = $this->name;
        $venueView->description = $this->description;
        $venueView->url = $this->url;
        if (null !== $this->location) {
            $venueView->location = $this->location->render(new LocationView());
        }

        return $venueView;
    }

    public function identity()
    {
        return $this->identity;
    }

    public function sameIdentityAs($other)
    {
        if ($this === $other) {
            return true;
        }

        if (! $other instanceof Artwork) {
            return false;
        }

        return (Venue) $other->identity() === $this->identity;
    }
}
