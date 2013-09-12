<?php

namespace Ninjagrl\Domain\Model\Venue;

use Ninjagrl\Domain\Model\Venue\View\LocationView;
use Ninjagrl\Domain\Shared\ValueObjectInterface;

class Location implements ValueObjectInterface
{
    private $addressLineOne;
    private $addressLineTwo;
    private $addressLineThree;
    private $city;
    private $state;
    private $zipcode;
    private $country;

    public function __construct(
    ) {
        $this->addressLineOne = $addressLineOne;
        $this->addressLineTwo = $addressLineTwo;
        $this->addressLineThree = $addressLineThree;
        $this->city = $city;
        $this->state = $state;
        $this->zipcode = $zipcode;
        $this->country = $country;
    }

    public function render(LocationView $locationView)
    {
        $locationView->addressLineOne = $this->addressLineOne;
        $locationView->addressLineTwo = $this->addressLineTwo;
        $locationView->addressLineThree = $this->addressLineThree;
        $locationView->city = $this->city;
        $locationView->state = $this->state;
        $locationView->zipcode = $this->zipcode;
        $locationView->country = $this->country;

        return $locationView;
    }

    public function sameValueAs($other = null)
    {
        if (null === $other) {
            return false;
        }

        if ($this === $other) {
            return true;
        }

        if (! $other instanceof __CLASS__) {
            return false;
        }

        $locationView = (Location) $other->render(new LocationView());

        return
            $this->addressLineOne === $locationView->addressLineOne &&
            $this->addressLineTwo === $locationView->addressLineTwo &&
            $this->addressLineThree === $locationView->addressLineThree &&
            $this->city === $locationView->city &&
            $this->state === $locationView->state &&
            $this->zipcode === $locationView->zipcode &&
            $this->country === $locationView->country;
    }

    public function copy()
    {
        return new static(
            $this->addressLineOne,
            $this->addressLineTwo,
            $this->addressLineThree,
            $this->city,
            $this->state,
            $this->zipcode,
            $this->country
        );
    }
}
