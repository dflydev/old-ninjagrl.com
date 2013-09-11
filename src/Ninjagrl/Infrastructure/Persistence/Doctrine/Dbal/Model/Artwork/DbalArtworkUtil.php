<?php

namespace Ninjagrl\Infrastructure\Persistence\Doctrine\Dbal\Model\Artwork;

use Doctrine\Dbal\Connection;
use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

class DbalArtworkUtil
{
    public static function updateArtworkSchema(Connection $connection)
    {
        $schemaManager = $connection->getSchemaManager();

        $fromSchema = $schemaManager->createSchema();
        $toSchema = static::getArtworkSchema();

        $comparator = new Comparator();
        $diff = $comparator->compare($fromSchema, $toSchema);

        foreach ($diff->toSaveSql($connection->getDatabasePlatform()) as $sql) {
            $connection->exec($sql);
        }
    }

    protected static function getArtworkSchema()
    {
        $schema = new Schema();

        $artworkTable = $schema->createTable('artwork');
        $artworkTable->addColumn('identity', 'string');
        $artworkTable->addColumn('title', 'string', array('notnull' => false,));
        $artworkTable->addColumn('description', 'text', array('notnull' => false,));
        $artworkTable->addColumn('size', 'string', array('notnull' => false,));
        $artworkTable->addColumn('medium', 'string', array('notnull' => false,));
        $artworkTable->addColumn('is_available', 'boolean', array('default' => false,));
        $artworkTable->addColumn('purchase_url', 'string', array('notnull' => false,));
        $artworkTable->addColumn('created', 'date', array('notnull' => false,));
        $artworkTable->addColumn('primary_image_identity', 'string', array('notnull' => false,));
        $artworkTable->setPrimaryKey(array('identity'));

        $artworkCategoryTable = $schema->createTable('artwork_category');
        $artworkCategoryTable->addColumn('artwork_identity', 'string');
        $artworkCategoryTable->addColumn('category_identity', 'string');
        $artworkCategoryTable->setPrimaryKey(array('artwork_identity', 'category_identity'));
        $artworkCategoryTable->addForeignKeyConstraint($artworkTable, array('artwork_identity'), array('identity'), array(
            'onUpdate' => 'CASCADE',
            'onDelete' => 'CASCADE'
        ));

        $artworkTag = $schema->createTable('artwork_tag');
        $artworkTag->addColumn('artwork_identity', 'string');
        $artworkTag->addColumn('tag_identity', 'string');
        $artworkTag->setPrimaryKey(array('artwork_identity', 'tag_identity'));
        $artworkTag->addForeignKeyConstraint($artworkTable, array('artwork_identity'), array('identity'), array(
            'onUpdate' => 'CASCADE',
            'onDelete' => 'CASCADE'
        ));

        $artworkImage = $schema->createTable('artwork_image');
        $artworkImage->addColumn('artwork_identity', 'string');
        $artworkImage->addColumn('image_identity', 'string');
        $artworkImage->setPrimaryKey(array('artwork_identity', 'image_identity'));
        $artworkImage->addForeignKeyConstraint($artworkTable, array('artwork_identity'), array('identity'), array(
            'onUpdate' => 'CASCADE',
            'onDelete' => 'CASCADE'
        ));

        return $schema;
    }
}
