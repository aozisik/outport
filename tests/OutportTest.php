<?php

use Aozisik\Outport\Outport;

class OutportTest extends TestCase
{
    public function testItMigratesDataCorrectly()
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

        $path = (new Outport())->table('books', collect($books))->go();
        $this->assertFileExists($path);

        Config::set('database.connections.outport-test', [
            'driver' => 'sqlite',
            'database' => $path,
            'prefix' => '',
        ]);

        $readFromSQLite = DB::connection('outport-test')
            ->table('books')
            ->select('title', 'author')
            ->get()
            ->map(function ($item) {
                return (array)$item;
            })->toArray();

        $this->assertEquals($books, $readFromSQLite);
        unlink($path);
    }
}
