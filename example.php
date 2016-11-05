<?php

use Aozisik\Outport\Outport;

$books = collect([
    [
        'title' => 'Pride and Prejudice',
        'author' => 'Jane Austen'
    ],
    [
        'title' => 'Oliver Twist',
        'author' => 'Charles Dickens'
    ],
    [
        'title' => ''
    ]
]);

$sqliteFile = (new Outport())
    ->tableFromCollection('books', $books)
    ->go();

echo $sqliteFile; // Path to your outported sqlite database