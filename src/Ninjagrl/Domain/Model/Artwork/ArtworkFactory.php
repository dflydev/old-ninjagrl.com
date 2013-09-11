<?php

namespace Ninjagrl\Domain\Model\Artwork;

use Ninjagrl\Domain\Model\Artwork\View\ArtworkView;
use Ninjagrl\Domain\Model\Category\CategoryIdentity;
use Ninjagrl\Domain\Model\Image\ImageIdentity;
use Ninjagrl\Domain\Model\Tag\TagIdentity;

class ArtworkFactory
{
    private $artworkPrototype;
    private $mappings;

    public function __construct()
    {
        $artworkReflection = new \ReflectionClass("Ninjagrl\Domain\Model\Artwork\Artwork");

        $isPhp54OrLater = version_compare(PHP_VERSION, '5.4.0', '>=');
        if ($isPhp54OrLater) {
            $this->artworkPrototype = $artworkReflection->newInstanceWithoutConstructor();
        } else {
            $toClass = 'Ninjagrl\Domain\Model\Artwork\Artwork';
            $this->artworkPrototype = unserialize('O:'.strlen($toClass).':"'.$toClass.'":0:{}');
        }

        $buildProperty = function ($propertyName) use ($artworkReflection) {
            $property = $artworkReflection->getProperty($propertyName);
            $property->setAccessible(true);

            return $property;
        };

        $buildCollection = function ($factory, $value = null) {
            if (null === $value) {
                return array();
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
                'property' => $buildProperty('identity'),
                'transformValue' => function ($value = null) {
                    if (null === $value) {
                        return null;
                    }

                    return new ArtworkIdentity($value);
                }
            ),
            'title' => array(
                'property' => $buildProperty('title'),
                'transformValue' => function ($value = null) {
                    return new ArtworkIdentity($value);
                }
            ),
            'title' => array(
                'property' => $buildProperty('title'),
            ),
            'description' => array(
                'property' => $buildProperty('description'),
            ),
            'size' => array(
                'property' => $buildProperty('size'),
            ),
            'medium' => array(
                'property' => $buildProperty('medium'),
            ),
            'is_available' => array(
                'property' => $buildProperty('isAvailable'),
            ),
            'purchase_url' => array(
                'property' => $buildProperty('purchaseUrl'),
            ),
            'created' => array(
                'property' => $buildProperty('created'),
                'transformValue' => function ($value = null) {
                    if (null === $value) {
                        return null;
                    }

                    return new \DateTime($value);
                }
            ),
            'category_identities' => array(
                'property' => $buildProperty('categoryIdentities'),
                'transformValue' => function ($value = null) use ($buildCollection) {
                    return $buildCollection(function ($identity) {
                        return new CategoryIdentity($identity);
                    }, $value);
                }
            ),
            'tag_identities' => array(
                'property' => $buildProperty('tagIdentities'),
                'transformValue' => function ($value = null) use ($buildCollection) {
                    return $buildCollection(function ($identity) {
                        return new TagIdentity($identity);
                    }, $value);
                }
            ),
            'image_identities' => array(
                'property' => $buildProperty('imageIdentities'),
                'transformValue' => function ($value = null) use ($buildCollection) {
                    return $buildCollection(function ($identity) {
                        return new ImageIdentity($identity);
                    }, $value);
                }
            ),
            'primary_image_identity' => array(
                'property' => $buildProperty('primaryImageIdentity'),
                'transformValue' => function ($value = null) {
                    if (null === $value) {
                        return null;
                    }

                    return new ImageIdentity($value);
                }
            ),
        );
    }

    public function createFromData(array $data)
    {
        $artwork = clone($this->artworkPrototype);

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
            'title' => $artworkView->title,
            'description' => $artworkView->description,
            'size' => $artworkView->size,
            'medium' => $artworkView->medium,
            'is_available' => $artworkView->isAvailable,
            'purchase_url' => $artworkView->purchaseUrl,
            'category_identities' => $artworkView->categoryIdentities,
            'tag_identities' => $artworkView->tagIdentities,
            'created' => $artworkView->created ? $artworkView->created->getTimestamp() : null,
            'image_identities' => $artworkView->imageIdentities,
            'primary_image_identity' => $artworkView->primaryImageIdentity,
        );

        return $this->createFromData($data);
    }
}
