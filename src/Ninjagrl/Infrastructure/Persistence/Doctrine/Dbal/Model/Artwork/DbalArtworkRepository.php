<?php

namespace Ninjagrl\Infrastructure\Persistence\Doctrine\Dbal\Model\Artwork;

use Doctrine\Dbal\Connection;
use Ninjagrl\Domain\Model\Artwork\Artwork;
use Ninjagrl\Domain\Model\Artwork\ArtworkFactory;
use Ninjagrl\Domain\Model\Artwork\ArtworkIdentity;
use Ninjagrl\Domain\Model\Artwork\ArtworkRepositoryInterface;

class DbalArtworkRepository implements ArtworkRepositoryInterface
{
    const SELECT_SQL = "
        SELECT
            a.identity,
            a.title,
            a.description,
            a.size,
            a.medium,
            a.is_available,
            a.purchase_url,
            a.created,
            a.primary_image_identity,
            ac.category_identity,
            at.tag_identity,
            ai.image_identity
          FROM artwork a
          LEFT JOIN
            artwork_category ac ON a.identity = ac.artwork_identity
          LEFT JOIN
            artwork_tag at ON a.identity = at.artwork_identity
          LEFT JOIN
            artwork_image ai ON a.identity = ai.artwork_identity
    ";

    private $connection;
    private $artworkFactory;
    private $identityMap = array();

    public function __construct(Connection $connection, ArtworkFactory $artworkFactory)
    {
        $this->connection = $connection;
        $this->artworkFactory = $artworkFactory;
    }

    function find(ArtworkIdentity $identity)
    {
        if (isset($this->identityMap[$identity->identity()])) {
            return $this->identityMap[$identity->identity()];
        }

        $rows = $this->connection->fetchAll(
            static::SELECT_SQL . " WHERE a.identity = ?",
            array($identity->identity())
        );

        if (empty($rows)) {
            return null;
        }

        $artworks = $this->rowsToArtwork($rows);
        $artwork = reset($artworks);

        return $artwork ?: null;
    }

    function findAll()
    {
        $rows = $this->connection->fetchAll(static::SELECT_SQL);

        if (empty($rows)) {
            return array();
        }

        return $this->rowsToArtwork($rows);
    }

    function add(Artwork $artwork)
    {
        if (isset($this->identityMap[$artwork->identity()->identity()])) {
            throw new \InvalidArgumentException(
                "An artwork with this identity already exists in the repository"
            );
        }

        $this->identityMap[$artwork->identity()->identity()] = $artwork;
    }

    public function flush()
    {
        foreach ($this->identityMap as $identity => $artwork) {
            if (isset($this->originalData[$identity])) {
                $artworkData = $this->updateArtworkIfDirty($artwork);
            } else {
                $artworkData = $this->insertArtwork($artwork);
            }
            $this->originalData[$identity] = $artworkData;
        }
    }

    private function updateArtworkIfDirty(Artwork $artwork)
    {
        //
    }

    private function updateArtwork(Artwork $artwork)
    {
        //
    }

    private function insertArtwork(Artwork $artwork)
    {
        $data = $this->artworkFactory->createDataFromArtwork($artwork);

        $artworkData = $data;

        $artworkCategoryIdentities = $artworkData['category_identities'];
        unset($artworkData['category_identities']);

        $artworkTagIdentities = $artworkData['tag_identities'];
        unset($artworkData['tag_identities']);

        $artworkImageIdentities = $artworkData['image_identities'];
        unset($artworkData['image_identities']);

        $this->connection->insert('artwork', $artworkData);

        foreach ($artworkCategoryIdentities as $categoryIdentity) {
            $this->connection->insert('artwork_category', array(
                'artwork_identity' => $artworkData['identity'],
                'category_identity' => $categoryIdentity,
            ));
        }

        foreach ($artworkTagIdentities as $tagIdentity) {
            $this->connection->insert('artwork_tag', array(
                'artwork_identity' => $artworkData['identity'],
                'tag_identity' => $tagIdentity,
            ));
        }

        foreach ($artworkImageIdentities as $imageIdentity) {
            $this->connection->insert('artwork_image', array(
                'artwork_identity' => $artworkData['identity'],
                'image_identity' => $imageIdentity,
            ));
        }

        return $data;
    }

    private function rowsToArtwork(array $rows)
    {
        $artworks = array();
        $groupedRows = array();

        foreach ($rows as $row) {
            if (!isset($groupedRows[$row['identity']])) {
                $groupedRows[$row['identity']] = array();
            }

            $groupedRows[$row['identity']][] = $row;
        }

        foreach ($groupedRows as $identity => $rows) {
            if (isset($this->identityMap[$identity])) {
                $artworks[] = $this->identityMap[$identity];

                continue;
            }

            $row = reset($rows);

            $data = array(
                'identity' => $row['identity'],
                'title' => $row['title'],
                'description' => $row['description'],
                'size' => $row['size'],
                'medium' => $row['medium'],
                'is_available' => $row['is_available'],
                'purchase_url' => $row['purchase_url'],
                'created' => $row['created'],
                'category_identities' => array(),
                'tag_identities' => array(),
                'primary_image_identity' => $row['primary_image_identity'],
                'image_identities' => array(),
            );

            foreach ($rows as $innerRow) {
                if (isset($innerRow['category_identity']) && ! in_array($innerRow['category_identity'], $data['category_identities'])) {
                    $data['category_identities'][] = $innerRow['category_identity'];
                }

                if (isset($innerRow['tag_identity']) && ! in_array($innerRow['tag_identity'], $data['tag_identities'])) {
                    $data['tag_identities'][] = $innerRow['tag_identity'];
                }

                if (isset($innerRow['image_identity']) && ! in_array($innerRow['image_identity'], $data['image_identities'])) {
                    $data['image_identities'][] = $innerRow['image_identity'];
                }
            }

            $artwork = $this->artworkFactory->createFromData($data);

            $this->originalData[$identity] = $data;

            $artworks[] = $this->identityMap[$id] = $artwork;
        }

        return $artworks;
    }
}
