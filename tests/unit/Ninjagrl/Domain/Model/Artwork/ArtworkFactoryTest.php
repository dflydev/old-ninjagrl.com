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

    /** @test */
    public function shouldCreateArtworkFromView()
    {
        $artworkFactory = new ArtworkFactory();

        $artworkViewInput = new View\ArtworkView();
        $artworkViewInput->identity = 'asdf-identity';
        $artworkViewInput->title = 'Hello World!';
        $artworkViewInput->description = 'Description goes here';
        $artworkViewInput->size = '3" x 3"';
        $artworkViewInput->medium = 'Oil';
        $artworkViewInput->created = new \DateTime('2013-09-10');
        $artworkViewInput->categoryIdentities = array(
            'category-000',
            'category-001',
            'category-002',
        );
        $artworkViewInput->tagIdentities = array(
            'tag-000',
            'tag-001',
            'tag-002',
        );
        $artworkViewInput->primaryImageIdentity = 'image-000';
        $artworkViewInput->imageIdentities = array(
            'image-000',
            'image-001',
            'image-002',
        );

        $artwork = $artworkFactory->createFromView($artworkViewInput);

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

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Collection must be an array
     */
    public function shouldThrowForNonArrayCategoryIdentities()
    {
        $artworkFactory = new ArtworkFactory();

        $artworkFactory->createFromData(array(
            'identity' => 'asdf-identity',
            'category_identities' => 'category-000',
        ));
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Collection must be an array
     */
    public function shouldThrowForNonArrayTagIdentities()
    {
        $artworkFactory = new ArtworkFactory();

        $artworkFactory->createFromData(array(
            'identity' => 'asdf-identity',
            'tag_identities' => 'tag-000',
        ));
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Collection must be an array
     */
    public function shouldThrowForNonArrayImageIdentities()
    {
        $artworkFactory = new ArtworkFactory();

        $artworkFactory->createFromData(array(
            'identity' => 'asdf-identity',
            'image_identities' => 'image-000',
        ));
    }
}
