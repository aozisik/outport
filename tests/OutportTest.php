<?php

use Aozisik\Outport\Outport;
use Illuminate\Support\Collection;

class OutportTest extends TestCase
{

    protected function runIntegrityTest($table, array $array)
    {
        $keys = array_keys($array[0]);
        $path = (new Outport())->table($table, Collection::make($array), ['title'])->go();
        $this->assertFileExists($path);

        Config::set('database.connections.outport-test', [
            'driver' => 'sqlite',
            'database' => $path,
            'prefix' => '',
        ]);

        $readFromSQLite = DB::connection('outport-test')
            ->table($table)
            ->select($keys)
            ->get();

        $readFromSQLite = array_map(function($item) {
            return (array)$item;
        }, $readFromSQLite);

        $this->assertEquals($array, $readFromSQLite);
        unlink($path);
    }

    public function testItMigratesArbitraryDataCorrectly()
    {
        $books = [
            [
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen'
            ],
            [
                'title' => 'Oliver Twist',
                'author' => 'Charles Dickens'
            ],
            [
                'title' => 'The Fault in Our Stars',
                'author' => 'John Green'
            ]
        ];

        $this->runIntegrityTest('books', $books);
    }

    public function testItMigratesEloquentDataCorrectly()
    {
        $books = [
            [
                'id' => 1,
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen'
            ],
            [
                'id' => 2,
                'title' => 'Oliver Twist',
                'author' => 'Charles Dickens'
            ],
            [
                'id' => 3,
                'title' => 'The Fault in Our Stars',
                'author' => 'John Green'
            ]
        ];

        $this->runIntegrityTest('books', $books);
    }
}