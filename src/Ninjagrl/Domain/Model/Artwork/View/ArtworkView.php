<?php

namespace Ninjagrl\Domain\Model\Artwork\View;

class ArtworkView
{
    public $identifier;
    public $title;
    public $description;
    public $size;
    public $medium;
    public $isAvailable;
    public $purchaseUrl;
    public $categoryIdentities = array();
    public $tagIdentities = array();
    public $created;
    public $primaryImageIdentity;
    public $imageIdentities = array();
}
