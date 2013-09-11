<?php

namespace Ninjagrl\Infrastructure\Persistence\Doctrine\Dbal\Model\Artwork;

use Ninjagrl\Domain\Model\Artwork\ArtworkFactory;
use Ninjagrl\Domain\Model\Artwork\ArtworkIdentity;
use Ninjagrl\Domain\Model\Artwork\View\ArtworkView;

class DbalArtworkRepositoryTest extends \PHPUnit_Framework_TestCase
{
    private $connection;

    public function setup()
    {
        $params = array(
            'driver' => 'pdo_sqlite',
            'memory' => true,
        );

        $this->connection = \Doctrine\DBAL\DriverManager::getConnection($params);

        DbalArtworkUtil::updateArtworkSchema($this->connection);

        $this->createFixtures();
    }

    /** @test */
    public function shouldFindAllArtwork()
    {
        $artworkRepository = new DbalArtworkRepository(
            $this->connection,
            new ArtworkFactory()
        );

        $artworks = $artworkRepository->findAll();

        $this->assertCount(3, $artworks);

        $foundArtwork001 = false;
        foreach ($artworks as $artwork) {
            if ($artwork->identity()->sameValueAs(new ArtworkIdentity('artwork-001'))) {
                $this->validateArtwork001($artwork);

                $foundArtwork001 = true;

                break;
            }
        }

        if (! $foundArtwork001) {
            $this->fail('Did not find expected "artwork-001" in findAll() results!');
        }
    }

    /** @test */
    public function shouldFindArtwork()
    {
        $artworkRepository = new DbalArtworkRepository(
            $this->connection,
            new ArtworkFactory()
        );

        $artwork = $artworkRepository->find(new ArtworkIdentity('artwork-001'));

        $this->validateArtwork001($artwork);
    }

    private function validateArtwork001($artwork)
    {
        $artworkView = $artwork->render(new ArtworkView());

        $this->assertEquals('artwork-001', $artworkView->identity);
        $this->assertEquals('somebody cares', $artworkView->title);
        $this->assertNull($artworkView->description);
        $this->assertEquals('8" x 10"', $artworkView->size);
        $this->assertEquals('acrylic on canvas', $artworkView->medium);
        $this->assertEquals(new \DateTime('2012-12-01'), $artworkView->created);
        $this->assertEquals(array('painting'), $artworkView->categoryIdentities);
        $this->assertEquals(array('dino', 'lighting'), $artworkView->tagIdentities);
        $this->assertEquals('image-001-002', $artworkView->primaryImageIdentity);
        $this->assertEquals(
            array('image-001-000', 'image-001-001', 'image-001-002', 'image-001-003'),
            $artworkView->imageIdentities
        );
    }

    private function createFixtures()
    {
        $this->createArtwork(array(
            'identity' => 'artwork-000',
            'title' => 'Robot Rock',
            'is_available' => true,
            'category_identities' => array(
                'painting',
            ),
            'tag_identities' => array(
                'mecha',
                'robot',
            ),
            'primary_image_identity' => 'image-000-003',
            'image_identities' => array(
                'image-000-000',
                'image-000-001',
                'image-000-002',
                'image-000-003',
            ),
        ));

        $this->createArtwork(array(
            'identity' => 'artwork-001',
            'title' => 'somebody cares',
            'size' => '8" x 10"',
            'medium' => 'acrylic on canvas',
            'is_available' => true,
            'category_identities' => array(
                'painting',
            ),
            'tag_identities' => array(
                'dino',
                'lighting',
            ),
            'created' => '2012-12-01',
            'primary_image_identity' => 'image-001-002',
            'image_identities' => array(
                'image-001-000',
                'image-001-001',
                'image-001-002',
                'image-001-003',
            ),
        ));

        $this->createArtwork(array(
            'identity' => 'artwork-002',
            'is_available' => false,
            'category_identities' => array(
                'painting',
            ),
        ));
    }

    private function createArtwork(array $data = array())
    {
        $data = array_merge(array(
            'identity' => uniqid('artwork-'),
            'category_identities' => array(),
            'tag_identities' => array(),
            'image_identities' => array(),
        ), $data);

        $categoryIdentities = $data['category_identities'];
        unset($data['category_identities']);

        $tagIdentities = $data['tag_identities'];
        unset($data['tag_identities']);

        $imageIdentities = $data['image_identities'];
        unset($data['image_identities']);

        $this->connection->insert('artwork', $data);

        foreach ($categoryIdentities as $categoryIdentity) {
            $this->connection->insert('artwork_category', array(
                'artwork_identity' => $data['identity'],
                'category_identity' => $categoryIdentity,
            ));
        }

        foreach ($tagIdentities as $tagIdentity) {
            $this->connection->insert('artwork_tag', array(
                'artwork_identity' => $data['identity'],
                'tag_identity' => $tagIdentity,
            ));
        }

        foreach ($imageIdentities as $imageIdentity) {
            $this->connection->insert('artwork_image', array(
                'artwork_identity' => $data['identity'],
                'image_identity' => $imageIdentity,
            ));
        }
    }
}
