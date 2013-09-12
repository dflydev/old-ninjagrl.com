<?php

namespace Ninjagrl\Domain\Model\Artwork;

use Ninjagrl\Domain\Model\Category\CategoryIdentity;
use Ninjagrl\Domain\Model\Image\ImageIdentity;
use Ninjagrl\Domain\Model\Tag\TagIdentity;

class ArtworkTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function shouldBeAbleToCreateEmptyArtwork()
    {
        $artwork = new Artwork(new ArtworkIdentity('artwork-000'));

        $this->assertEquals('artwork-000', $artwork->identity()->identity());
    }

    /** @test */
    public function shouldBeAbleToSetBasicInformationFromConstructor()
    {
        $artwork = new Artwork(
            new ArtworkIdentity('artwork-000'),
            'Some Title',
            'Some Description'
        );

        $artworkView = $artwork->render(new View\ArtworkView());

        $this->assertEquals('Some Title', $artworkView->title);
        $this->assertEquals('Some Description', $artworkView->description);
    }

    public function provideSetBasicInformation()
    {
        $artwork = new Artwork(
            new ArtworkIdentity('artwork-000'),
            'Some Title',
            'Some Description'
        );

        return array(
            array($artwork, null, null, null, null, null, null),
            array($artwork, 'some title', null, null, 'some title', null, null),
            array($artwork, null, 'some desc', null, null, 'some desc', null),
            array($artwork, null, null, new \DateTime('2013-09-10'), null, null, new \DateTime('2013-09-10')),
            array($artwork, 'A', 'B', new \DateTime('2013-09-10'), 'A', 'B', new \DateTime('2013-09-10')),
        );
    }

    /**
     * @test
     * @dataProvider provideSetBasicInformation
     */
    public function shouldBeAbleToSetBasicInformation(
        $artwork,
        $setTitle = null,
        $setDescription = null,
        $setCreated = null,
        $expectedTitle = null,
        $expectedDescription = null,
        $expectedCreated = null
    ) {
        $artwork->setBasicInformation($setTitle, $setDescription, $setCreated);

        $artworkView = $artwork->render(new View\ArtworkView());

        $this->assertEquals($expectedTitle, $artworkView->title);
        $this->assertEquals($expectedDescription, $artworkView->description);
        $this->assertEquals($expectedCreated, $artworkView->created);
    }

    public function provideSetPhysicalDescription()
    {
        $artwork = new Artwork(new ArtworkIdentity('artwork-000'));

        return array(
            array($artwork, null, null, null, null),
            array($artwork, 'some size', null, 'some size', null),
            array($artwork, null, 'some medium', null, 'some medium'),
            array($artwork, 'some size', 'some medium', 'some size', 'some medium'),
        );
    }

    /**
     * @test
     * @dataProvider provideSetPhysicalDescription
     */
    public function shouldBeAbleToSetPhysicalDescription(
        $artwork,
        $setSize = null,
        $setMedium = null,
        $expectedSize = null,
        $expectedMedium = null
    ) {
        $artwork->setPhysicalDescription($setSize, $setMedium);

        $artworkView = $artwork->render(new View\ArtworkView());

        $this->assertEquals($expectedSize, $artworkView->size);
        $this->assertEquals($expectedMedium, $artworkView->medium);
    }

    /**
     * @test
     */
    public function shouldBeUnavailableByDefault()
    {
        $artwork = new Artwork(new ArtworkIdentity('artwork-000'));

        $artworkView = $artwork->render(new View\ArtworkView());

        $this->assertFalse($artworkView->isAvailable);
        $this->assertNull($artworkView->purchaseUrl);
    }

    /**
     * @test
     */
    public function shouldBeAbleToBeAvailableWithPurchaseUrl()
    {
        $artwork = new Artwork(new ArtworkIdentity('artwork-000'));

        $artwork->makeAvailable('http://etsy.com/buy-me');

        $artworkView = $artwork->render(new View\ArtworkView());

        $this->assertTrue($artworkView->isAvailable);
        $this->assertEquals('http://etsy.com/buy-me', $artworkView->purchaseUrl);
    }

    /**
     * @test
     */
    public function shouldBeAbleToBeAvailableWithoutPurchaseUrl()
    {
        $artwork = new Artwork(new ArtworkIdentity('artwork-000'));

        $artwork->makeAvailable();

        $artworkView = $artwork->render(new View\ArtworkView());

        $this->assertTrue($artworkView->isAvailable);
        $this->assertNull($artworkView->purchaseUrl);
    }

    /**
     * @test
     */
    public function shouldNotClearPurchaseUrlWhenMakingUnavailable()
    {
        $artwork = new Artwork(new ArtworkIdentity('artwork-000'));

        $artwork->makeAvailable('http://etsy.com/buy-me');
        $artwork->makeUnavailable();

        $artworkView = $artwork->render(new View\ArtworkView());

        $this->assertFalse($artworkView->isAvailable);
        $this->assertEquals('http://etsy.com/buy-me', $artworkView->purchaseUrl);
    }

    public function provideSetCategoryIdentities()
    {
        $artwork = new Artwork(new ArtworkIdentity('artwork-000'));

        return array(
            array($artwork, array(), array()),
            array(
                $artwork,
                array(
                    new CategoryIdentity('category-000'),
                ),
                array(
                    'category-000',
                ),
            ),
            array(
                $artwork,
                array(
                    new CategoryIdentity('category-000'),
                    new CategoryIdentity('category-001'),
                    new CategoryIdentity('category-002'),
                ),
                array(
                    'category-000',
                    'category-001',
                    'category-002',
                ),
            ),
        );
    }

    /**
     * @test
     * @dataProvider provideSetCategoryIdentities
     */
    public function shouldBeAbleToSetCategoryIdentities(
        $artwork,
        $setCategoryIdentities = null,
        $expectedCategoryIdentities = null
    ) {
        $artwork->setCategoryIdentities($setCategoryIdentities);

        $artworkView = $artwork->render(new View\ArtworkView());

        $this->assertEquals($expectedCategoryIdentities, $artworkView->categoryIdentities);
    }

    public function provideSetTagIdentities()
    {
        $artwork = new Artwork(new ArtworkIdentity('artwork-000'));

        return array(
            array($artwork, array(), array()),
            array(
                $artwork,
                array(
                    new TagIdentity('tag-000'),
                ),
                array(
                    'tag-000',
                ),
            ),
            array(
                $artwork,
                array(
                    new TagIdentity('tag-000'),
                    new TagIdentity('tag-001'),
                    new TagIdentity('tag-002'),
                ),
                array(
                    'tag-000',
                    'tag-001',
                    'tag-002',
                ),
            ),
        );
    }

    /**
     * @test
     * @dataProvider provideSetTagIdentities
     */
    public function shouldBeAbleToSetTagIdentities(
        $artwork,
        $setTagIdentities = null,
        $expectedTagIdentities = null
    ) {
        $artwork->setTagIdentities($setTagIdentities);

        $artworkView = $artwork->render(new View\ArtworkView());

        $this->assertEquals($expectedTagIdentities, $artworkView->tagIdentities);
    }

    public function provideSetImageIdentities()
    {
        $artwork = new Artwork(new ArtworkIdentity('artwork-000'));

        return array(
            array($artwork, array(), array()),
            array(
                $artwork,
                array(
                    new ImageIdentity('image-000'),
                ),
                array(
                    'image-000',
                ),
            ),
            array(
                $artwork,
                array(
                    new ImageIdentity('image-000'),
                    new ImageIdentity('image-001'),
                    new ImageIdentity('image-002'),
                ),
                array(
                    'image-000',
                    'image-001',
                    'image-002',
                ),
            ),
        );
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage must already be an image associated with this piece of artwork
     */
    public function shouldNotBeAbleToSetPrimaryImageWithNoImages()
    {
        $artwork = new Artwork(new ArtworkIdentity('artwork-000'));

        $artwork->setPrimaryImageIdentity(new ImageIdentity('image-000'));
    }

    /**
     * @test
     */
    public function shouldSetPrimaryImageToFirstImageIfNotAlreadySet()
    {
        $artwork = new Artwork(new ArtworkIdentity('artwork-000'));

        $artwork->setImageIdentities(array(
            new ImageIdentity('image-000'),
            new ImageIdentity('image-001'),
            new ImageIdentity('image-002'),
        ));

        $artworkView = $artwork->render(new View\ArtworkView());

        $this->assertEquals('image-000', $artworkView->primaryImageIdentity);
    }

    /**
     * @test
     */
    public function shouldBeAbleToSetPrimaryImage()
    {
        $artwork = new Artwork(new ArtworkIdentity('artwork-000'));

        $artwork->setImageIdentities(array(
            new ImageIdentity('image-000'),
            new ImageIdentity('image-001'),
            new ImageIdentity('image-002'),
        ));

        $artwork->setPrimaryImageIdentity(new ImageIdentity('image-002'));

        $artworkView = $artwork->render(new View\ArtworkView());

        $this->assertEquals('image-002', $artworkView->primaryImageIdentity);
    }

    /**
     * @test
     */
    public function shouldResetPrimaryImageIfPrimaryImageIsRemoved()
    {
        $artwork = new Artwork(new ArtworkIdentity('artwork-000'));

        $artwork->setImageIdentities(array(
            new ImageIdentity('image-000'),
            new ImageIdentity('image-001'),
            new ImageIdentity('image-002'),
        ));

        $artwork->setPrimaryImageIdentity(new ImageIdentity('image-002'));

        $artwork->setImageIdentities(array(
            new ImageIdentity('image-003'),
            new ImageIdentity('image-004'),
            new ImageIdentity('image-005'),
        ));

        $artworkView = $artwork->render(new View\ArtworkView());

        $this->assertEquals('image-003', $artworkView->primaryImageIdentity);
    }

    /**
     * @test
     */
    public function shouldUnsetPrimaryImageIfAllImagesAreRemoved()
    {
        $artwork = new Artwork(new ArtworkIdentity('artwork-000'));

        $artwork->setImageIdentities(array(
            new ImageIdentity('image-000'),
            new ImageIdentity('image-001'),
            new ImageIdentity('image-002'),
        ));

        $artwork->setPrimaryImageIdentity(new ImageIdentity('image-002'));

        $artwork->setImageIdentities(array());

        $artworkView = $artwork->render(new View\ArtworkView());

        $this->assertEquals(null, $artworkView->primaryImageIdentity);
    }

    /**
     * @test
     * @dataProvider provideSetImageIdentities
     */
    public function shouldBeAbleToSetImageIdentities(
        $artwork,
        $setImageIdentities = null,
        $expectedImageIdentities = null
    ) {
        $artwork->setImageIdentities($setImageIdentities);

        $artworkView = $artwork->render(new View\ArtworkView());

        $this->assertEquals($expectedImageIdentities, $artworkView->imageIdentities);
    }

    /** @test */
    public function shouldNotCompareIdentityToNull()
    {
        $artwork = new Artwork(new ArtworkIdentity('asdf-identity'));

        $this->assertFalse($artwork->sameIdentityAs(null));
    }

    /** @test */
    public function shouldCompareIdentityToSameObject()
    {
        $artwork = new Artwork(new ArtworkIdentity('asdf-identity'));

        $this->assertTrue($artwork->sameIdentityAs($artwork));
    }

    /** @test */
    public function shouldNotCompareIdentityToDifferentTypeOfObject()
    {
        $artwork = new Artwork(new ArtworkIdentity('asdf-identity'));

        $this->assertFalse($artwork->sameIdentityAs($this));
    }

    /** @test */
    public function shouldCompareIdentityToDifferentObjectWithSameIdentity()
    {
        $artwork = new Artwork(new ArtworkIdentity('asdf-identity'));

        $this->assertTrue($artwork->sameIdentityAs(new Artwork(new ArtworkIdentity('asdf-identity'))));
    }

    /** @test */
    public function shouldNotCompareIdentityToDifferentIdentity()
    {
        $artwork = new Artwork(new ArtworkIdentity('asdf-identity'));

        $this->assertFalse($artwork->sameIdentityAs(new Artwork(new ArtworkIdentity('foo-identity'))));
    }

    /** @test */
    public function shouldRender()
    {
        $artwork = new Artwork(new ArtworkIdentity('asdf-identity'));
        $artwork->setBasicInformation('Hello World!', 'Description goes here', new \DateTime('2013-09-10'));
        $artwork->setPhysicalDescription('3" x 3"', 'Oil');
        $artwork->makeAvailable('http://etsy.com/buy-me');
        $artwork->setCategoryIdentities(array(
            new CategoryIdentity('category-000'),
            new CategoryIdentity('category-001'),
            new CategoryIdentity('category-002'),
        ));
        $artwork->setTagIdentities(array(
            new TagIdentity('tag-000'),
            new TagIdentity('tag-001'),
            new TagIdentity('tag-002'),
        ));
        $artwork->setImageIdentities(array(
            new ImageIdentity('image-000'),
            new ImageIdentity('image-001'),
            new ImageIdentity('image-002'),
        ));

        $artworkView = $artwork->render(new View\ArtworkView());

        $this->assertEquals('asdf-identity', $artworkView->identity);
        $this->assertEquals('Hello World!', $artworkView->title);
        $this->assertEquals('Description goes here', $artworkView->description);
        $this->assertEquals('3" x 3"', $artworkView->size);
        $this->assertEquals('Oil', $artworkView->medium);
        $this->assertTrue($artworkView->isAvailable);
        $this->assertEquals('http://etsy.com/buy-me', $artworkView->purchaseUrl);
        $this->assertEquals(new \DateTime('2013-09-10'), $artworkView->created);
        $this->assertEquals(array('category-000', 'category-001', 'category-002'), $artworkView->categoryIdentities);
        $this->assertEquals(array('tag-000', 'tag-001', 'tag-002'), $artworkView->tagIdentities);
        $this->assertEquals('image-000', $artworkView->primaryImageIdentity);
        $this->assertEquals(array('image-000', 'image-001', 'image-002'), $artworkView->imageIdentities);
    }
}
