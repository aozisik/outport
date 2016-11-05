##Outport
**[Work In Progress]**

Outport is a Laravel package that helps you create SQLite databases from arbitrary Laravel collections.
Just pass in your collections and set table names.

Outport will:
+ Decide the best way to create tables for your collections
+ Create a SQLite database and migrate those tables
+ Insert data from your collection in chunks

Then you will get a path to the resulting SQLite file.

Example use:

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
        ->table('books', $books)
        ->go();
    
    echo $sqliteFile; // Path to your sqlite database

Stay tuned for updates!

