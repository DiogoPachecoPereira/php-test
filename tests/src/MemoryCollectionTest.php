<?php

namespace Live\Collection;

use PHPUnit\Framework\TestCase;

class MemoryCollectionTest extends TestCase
{
    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $collection = new MemoryCollection();
        return $collection;
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     * @doesNotPerformAssertions
     */
    public function dataCanBeAdded()
    {
        $collection = new MemoryCollection();
        $collection->set('index1', 'value');
        $collection->set('index2', 5);
        $collection->set('index3', true);
        $collection->set('index4', 6.5);
        $collection->set('index5', ['data']);
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function dataCanBeRetrieved()
    {
        $collection = new MemoryCollection();
        $collection->set('index1', 'value');

        $this->assertEquals('value', $collection->get('index1'));
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function inexistentIndexShouldReturnDefaultValue()
    {
        $collection = new MemoryCollection();

        $this->assertNull($collection->get('index1'));
        $this->assertEquals('defaultValue', $collection->get('index1', 'defaultValue'));
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function newCollectionShouldNotContainItems()
    {
        $collection = new MemoryCollection();
        $this->assertEquals(0, $collection->count());
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function collectionWithItemsShouldReturnValidCount()
    {
        $collection = new MemoryCollection();
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
        $collection = new MemoryCollection();
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
        $collection = new MemoryCollection();
        $collection->set('index', 'value');

        $this->assertTrue($collection->has('index'));
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function expiredItemShouldNotReturn()
    {
        $collection = new MemoryCollection();
        $collection->set('index', 'value', 0);
        sleep(0.5);
        $this->assertEquals('defaultValue', $collection->get('index1', 'defaultValue'));
        $collection->clean();
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function shouldUpdateFile()
    {
        $collection = new MemoryCollection();
        $collection->set('index1', 'value', 60);
        $collection->set('index2', 5, 60);
        $collection->set('index3', true, 60);

        $collection->set('index2', "resolve", 60);

        $this->assertEquals('resolve', $collection->get('index2', 'defaultValue'));
        $this->assertEquals(3, $collection->count());
        $collection->clean();
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function shouldReturnAnExistingIndex()
    {
        $collection = new MemoryCollection();
        $collection->set('index1', 'value', 60);
        $collection->set('index2', 5, 60);

        $this->assertTrue($collection->has('index1'));
        $this->assertFalse($collection->has('index20'));

        $collection->clean();
    }
}
