<?php

namespace Live\Collection;

use PHPUnit\Framework\TestCase;

class FileCollectionTest extends TestCase
{
    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $collection = new FileCollection();
        return $collection;
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     * @doesNotPerformAssertions
     */
    public function dataCanBeAdded()
    {
        $collection = new FileCollection();
        $collection->set("index0", 'valor');
        $collection->set("index1", 5, 10);
        $collection->set("index2", true);
        $collection->set("index3", 6.5, 10);
        $collection->set("index4", ['value1', 'value2', 'value3'], 10);
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function dataCanBeRetrieved()
    {
        $collection = new FileCollection();
        $collection->set('index1', 'value', 10);

        $this->assertEquals('value', $collection->get('index1'));
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function arrayValueMustBeFormated()
    {
        $collection = new FileCollection();
        $collection->set('index1', ['value1', 'value2'], 10);

        $this->assertEquals('value1;value2', $collection->get('index1'));
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function inexistentIndexShouldReturnDefaultValue()
    {
        $collection = new FileCollection();
        $this->assertNull($collection->get('index1'));
        $this->assertEquals('defaultValue', $collection->get('index1', 'defaultValue'));
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function newCollectionShouldNotContainItems()
    {
        $collection = new FileCollection();
        $this->assertEquals(0, $collection->count());
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function collectionWithItemsShouldReturnValidCount()
    {
        $collection = new FileCollection();
        $collection->set('index1', 'value');
        $collection->set('index2', 5);
        $collection->set('index3', true);

        $this->assertEquals(3, $collection->count());
    }

    /**
     * @test
     * @depends collectionWithItemsShouldReturnValidCount
     */
    public function collectionCanBeCleaned()
    {
        $collection = new FileCollection();
        $collection->set('index', 'value');
        $this->assertEquals(1, $collection->count());

        $collection->clean();
        $this->assertEquals(0, $collection->count());
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function addedItemShouldExistInCollection()
    {
        $collection = new FileCollection();
        $collection->set('index', 'value');

        $this->assertTrue($collection->has('index'));
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function expiredItemShouldNotReturn()
    {
        $collection = new FileCollection();
        $collection->set('index', 'value', -10);
        $this->assertNull($collection->get('index', 'defaultValue'));
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function shouldUpdateFile()
    {
        $collection = new FileCollection();
        $collection->set('index1', 'value', 60);
        $collection->set('index2', 5, 60);
        $collection->set('index3', true, 60);

        $collection->set('index2', "resolve", 60);

        $this->assertEquals('resolve', $collection->get('index2', 'defaultValue'));
        $this->assertEquals(3, $collection->count());
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function shouldReturnAnExistingIndex()
    {
        $collection = new FileCollection();
        $collection->set('index1', 'value', 60);
        $collection->set('index2', 5, 60);

        $this->assertTrue($collection->has('index1'));
        $this->assertFalse($collection->has('index20'));

        $collection->clean();
    }
}
