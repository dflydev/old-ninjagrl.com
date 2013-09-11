<?php

namespace Ninjagrl\Domain\Model\Artwork;

use Ninjagrl\Domain\Model\Artwork\View\ArtworkView;
use Ninjagrl\Domain\Model\Image\ImageIdentity;
use Ninjagrl\Domain\Shared\EntityInterface;

class Artwork implements EntityInterface
{
    private $identity;
    private $title;
    private $description;
    private $size;
    private $medium;
    private $isAvailable = false;
    private $purchaseUrl;
    private $categoryIdentities = array();
    private $tagIdentities = array();
    private $created;
    private $imageIdentities = array();
    private $primaryImageIdentity;

    public function __construct(ArtworkIdentity $identity, $title = null, $description = null, $created = null)
    {
        $this->identity = $identity;
        $this->setBasicInformation($title, $description, $created);
    }

    public function setBasicInformation($title = null, $description = null, $created = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->created = $created;

        return $this;
    }

    public function setPhysicalDescription($size = null, $medium = null)
    {
        $this->size = $size;
        $this->medium = $medium;

        return $this;
    }

    public function setPrimaryImageIdentity(ImageIdentity $imageIdentity = null)
    {
        foreach ($this->imageIdentities as $existingImageIdentity) {
            if ($existingImageIdentity->sameValueAs($imageIdentity)) {
                $this->primaryImageIdentity = $imageIdentity;

                return $this;
            }
        }

        throw new \RuntimeException(
            "Primary image must already be an image associated with this piece of artwork"
        );
    }

    public function setImageIdentities(array $imageIdentities = array())
    {
        $primaryImageIdentityIsValid = false;

        if (null !== $this->primaryImageIdentity) {
            foreach ($imageIdentities as $imageIdentity) {
                if ($this->primaryImageIdentity->sameValueAs($imageIdentity)) {
                    $primaryImageIdentityIsValid = true;

                    break;
                }
            }
        }

        if (! $primaryImageIdentityIsValid) {
            if (count($imageIdentities)) {
                $this->primaryImageIdentity = reset($imageIdentities);
            } else {
                $this->primaryImageIdentity = null;
            }
        }

        $this->imageIdentities = $imageIdentities;

        return $this;
    }

    public function setCategoryIdentities(array $categoryIdentities = array())
    {
        $this->categoryIdentities = $categoryIdentities;
    }

    public function setTagIdentities(array $tagIdentities = array())
    {
        $this->tagIdentities = $tagIdentities;
    }

    public function makeAvailable($purchaseUrl = null)
    {
        $this->isAvailable = true;
        $this->purchaseUrl = $purchaseUrl;

        return $this;
    }

    public function makeUnavailable()
    {
        $this->isAvailable = false;

        return $this;
    }

    public function render(ArtworkView $artworkView)
    {
        $artworkView->identity = $this->identity->identity();
        $artworkView->title = $this->title;
        $artworkView->description = $this->description;
        $artworkView->size = $this->size;
        $artworkView->medium = $this->medium;
        $artworkView->isAvailable = $this->isAvailable;
        $artworkView->purchaseUrl = $this->purchaseUrl;
        foreach ($this->categoryIdentities as $categoryIdentity) {
            $artworkView->categoryIdentities[] = $categoryIdentity->identity();
        }
        foreach ($this->tagIdentities as $tagIdentity) {
            $artworkView->tagIdentities[] = $tagIdentity->identity();
        }
        $artworkView->created = $this->created;
        foreach ($this->imageIdentities as $imageIdentity) {
            $artworkView->imageIdentities[] = $imageIdentity->identity();
        }
        $artworkView->primaryImageIdentity = $this->primaryImageIdentity
            ? $this->primaryImageIdentity->identity()
            : null;


        return $artworkView;
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

        return $this->identity->sameValueAs($other->identity());
    }
}
