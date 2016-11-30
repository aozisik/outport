<?php

use Aozisik\Outport\Connection;
use Illuminate\Support\Collection;
use Aozisik\Outport\Schema as OutportSchema;

class SchemaTest extends TestCase
{
    /**
     * @var Connection
     */
    private $connection;
    private $link;

    public function setUp()
    {
        parent::setUp();
        $this->connection = new Connection();
        $this->connection->open();
        $this->link = Schema::connection($this->connection->id);
    }

    public function tearDown()
    {
        $this->connection->close();
        unlink($this->connection->path);
    }

    public function testItMigratesTable()
    {
        $schema = new OutportSchema([
            'a' => 'text',
            'b' => 'integer'
        ]);

        $schema->create($this->connection, 'test');

        $this->assertTrue($this->link->hasColumn('test', 'id'));
        $this->assertTrue($this->link->hasColumn('test', 'a'));
        $this->assertTrue($this->link->hasColumn('test', 'b'));
    }

    public function testItCreatesIndexes()
    {
        $schema = new OutportSchema([
            'a' => 'text',
            'b' => 'integer'
        ]);

        $schema->create($this->connection, 'test', ['a']);
        $indexes = Collection::make(DB::connection($this->connection->id)
            ->select('PRAGMA index_list(test)'))
            ->map(function ($index) {
                $matches = [];
                preg_match('/^test_(.*?)_index$/', $index->name, $matches);
                return $matches[1];
            })->toArray();

        $this->assertNotEmpty($indexes);
        $this->assertContains('a', $indexes);
        $this->assertNotContains('b', $indexes);
    }
}