<?php

namespace Ninjagrl\Domain\Model\Artwork;

use Ninjagrl\Domain\Model\Artwork\View\ArtworkView;
use Ninjagrl\Domain\Model\Category\CategoryIdentity;
use Ninjagrl\Domain\Model\Image\ImageIdentity;
use Ninjagrl\Domain\Model\Tag\TagIdentity;
use Ninjagrl\Domain\Shared\PrototypeManager;

class ArtworkFactory
{
    private $prototypeManager;
    private $mappings;

    public function __construct(PrototypeManager $prototypeManager = null)
    {
        $this->prototypeManager = $prototypeManager ?: new PrototypeManager("Ninjagrl\Domain\Model\Artwork\Artwork");

        $buildCollection = function ($factory, $value) {
            if (! is_array($value)) {
                throw new \InvalidArgumentException("Collection must be an array.");
            }

            if (empty($value)) {
                return array();
            }

            $identities = array();
            foreach ($value as $identity) {
                $identities[] = $factory($identity);
            }

            return $identities;
        };

        $this->mapping = array(
            'identity' => array(
                'property' => $this->prototypeManager->buildProperty('identity'),
                'transformValue' => function ($value) {
                    return new ArtworkIdentity($value);
                }
            ),
            'title' => array(
                'property' => $this->prototypeManager->buildProperty('title'),
            ),
            'description' => array(
                'property' => $this->prototypeManager->buildProperty('description'),
            ),
            'size' => array(
                'property' => $this->prototypeManager->buildProperty('size'),
            ),
            'medium' => array(
                'property' => $this->prototypeManager->buildProperty('medium'),
            ),
            'is_available' => array(
                'property' => $this->prototypeManager->buildProperty('isAvailable'),
            ),
            'purchase_url' => array(
                'property' => $this->prototypeManager->buildProperty('purchaseUrl'),
            ),
            'created' => array(
                'property' => $this->prototypeManager->buildProperty('created'),
                'transformValue' => function ($value) {
                    return new \DateTime($value);
                }
            ),
            'category_identities' => array(
                'property' => $this->prototypeManager->buildProperty('categoryIdentities'),
                'transformValue' => function ($value) use ($buildCollection) {
                    return $buildCollection(function ($identity) {
                        return new CategoryIdentity($identity);
                    }, $value);
                }
            ),
            'tag_identities' => array(
                'property' => $this->prototypeManager->buildProperty('tagIdentities'),
                'transformValue' => function ($value) use ($buildCollection) {
                    return $buildCollection(function ($identity) {
                        return new TagIdentity($identity);
                    }, $value);
                }
            ),
            'image_identities' => array(
                'property' => $this->prototypeManager->buildProperty('imageIdentities'),
                'transformValue' => function ($value) use ($buildCollection) {
                    return $buildCollection(function ($identity) {
                        return new ImageIdentity($identity);
                    }, $value);
                }
            ),
            'primary_image_identity' => array(
                'property' => $this->prototypeManager->buildProperty('primaryImageIdentity'),
                'transformValue' => function ($value) {
                    return new ImageIdentity($value);
                }
            ),
        );
    }

    public function createFromData(array $data)
    {
        $artwork = $this->prototypeManager->createClone();

        foreach ($this->mapping as $key => $field) {
            if (isset($data[$key])) {
                $value = isset($field['transformValue'])
                    ? $field['transformValue']($data[$key])
                    : $data[$key];
                $field['property']->setValue($artwork, $value);
            }
        }

        return $artwork;
    }

    public function createFromView(ArtworkView $artworkView)
    {
        $data = array(
            'identity' => $artworkView->identity,
            'title' => $artworkView->title,
            'description' => $artworkView->description,
            'size' => $artworkView->size,
            'medium' => $artworkView->medium,
            'is_available' => $artworkView->isAvailable,
            'purchase_url' => $artworkView->purchaseUrl,
            'category_identities' => $artworkView->categoryIdentities,
            'tag_identities' => $artworkView->tagIdentities,
            'created' => $artworkView->created ? $artworkView->created->format('Y-m-d') : null,
            'image_identities' => $artworkView->imageIdentities,
            'primary_image_identity' => $artworkView->primaryImageIdentity,
        );

        return $this->createFromData($data);
    }
}
