<?php

namespace Ninjagrl\Domain\Model\Show;

use Ninjagrl\Domain\Model\Artwork\Artwork;
use Ninjagrl\Domain\Model\Show\View\ShowView;
use Ninjagrl\Domain\Model\Venue\Venue;
use Ninjagrl\Domain\Model\Venue\Venue\View\VenueView;

class Show
{
    private $identity;
    private $venue;
    private $name;
    private $description;
    private $opens;
    private $ends;

    public function construct($identity, Venue $venue, $name, $description = null, $opens = null, $ends = null)
    {
        $this->identity = $identity;
        $this->venue = $venue;
        $this->setBasicInformation($name, $description);
        $this->opens = $opens;
        $this->ends = $ends;
    }

    public function setBasicInformation(Venue $venue, $name, $description = null)
    {
        $this->venue = $venue;
        $this->name = $name;
        $this->description = $description;

        return $this;
    }

    public function setOpens(\DateTime $opens = null)
    {
        $this->opens = $opens;

        return $this;
    }

    public function setEnds(\DateTime $ends = null)
    {
        $this->ends = $ends;

        return $this;
    }

    public function end(\DateTime $ends = null)
    {
        $this->ends = $ends ?: new \DateTime();

        return $this;
    }

    public function render(ShowView $showView)
    {
        $showView->identity = $this->identity;
        $showView->venue = $this->venue->render(new VenueView());
        $showView->name = $this->name;
        $showView->description = $this->description;
        $showView->opens = $this->opens;
        $showView->ends = $this->ends;

        return $showView;
    }

    public function identity()
    {
        return $this->identity;
    }

    public function sameIdentityAs($other = null)
    {
        if (null === $other) {
            return false;
        }

        if ($this === $other) {
            return true;
        }

        if (! $other instanceof Artwork) {
            return false;
        }

        return $this->identity->sameValueAs($other->identity());
    }
}
