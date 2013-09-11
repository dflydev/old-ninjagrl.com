<?php

namespace Ninjagrl\Domain\Model\Artwork;

class ArtworkFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function shouldCreateArtworkFromData()
    {
        $artworkFactory = new ArtworkFactory();

        $artwork = $artworkFactory->createFromData(array(
            'identity' => 'asdf-identity',
            'title' => 'Hello World!',
            'description' => 'Description goes here',
            'size' => '3" x 3"',
            'medium' => 'Oil',
            'created' => '2013-09-10',
            'category_identities' => array(
                'category-000',
                'category-001',
                'category-002',
            ),
            'tag_identities' => array(
                'tag-000',
                'tag-001',
                'tag-002',
            ),
            'primary_image_identity' => 'image-000',
            'image_identities' => array(
                'image-000',
                'image-001',
                'image-002',
            ),
        ));

        $artworkView = $artwork->render(new View\ArtworkView());

        $this->assertEquals('asdf-identity', $artworkView->identity);
        $this->assertEquals('Hello World!', $artworkView->title);
        $this->assertEquals('Description goes here', $artworkView->description);
        $this->assertEquals('3" x 3"', $artworkView->size);
        $this->assertEquals('Oil', $artworkView->medium);
        $this->assertEquals(new \DateTime('2013-09-10'), $artworkView->created);
        $this->assertEquals(array('category-000', 'category-001', 'category-002'), $artworkView->categoryIdentities);
        $this->assertEquals(array('tag-000', 'tag-001', 'tag-002'), $artworkView->tagIdentities);
        $this->assertEquals('image-000', $artworkView->primaryImageIdentity);
        $this->assertEquals(array('image-000', 'image-001', 'image-002'), $artworkView->imageIdentities);
    }
}
