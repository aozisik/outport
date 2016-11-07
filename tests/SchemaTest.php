<?php

use Aozisik\Outport\Connection;
use Aozisik\Outport\Schema as OutportSchema;

class SchemaTest extends TestCase
{
    /**
     * @var Connection
     */
    private $connection;

    public function setUp()
    {
        parent::setUp();
        $this->connection = new Connection();
        $this->connection->open();
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

        $link = Schema::connection($this->connection->id);
        $this->assertTrue($link->hasColumn('test', 'id'));
        $this->assertTrue($link->hasColumn('test', 'a'));
        $this->assertTrue($link->hasColumn('test', 'b'));
    }
}