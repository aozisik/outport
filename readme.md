⚓️ Outport [![Build Status](https://travis-ci.org/aozisik/outport.svg?branch=L4)](https://travis-ci.org/aozisik/outport)
========


Just pass in Laravel collections and Outport will put them all in a SQLite database.
    
##How do I use it?

You should first require this package through composer:

    composer require aozisik/outport

Then simply:

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
			'title' => 'The Fault in Our Stars',
			'author' => 'John Green'
       ]
	]);
    
    $sqliteFile = (new Outport())
        ->table('books', $books, ['author']) // table name, collection, indexes
        ->go();
    
    echo $sqliteFile; // Path to your sqlite database

##I want details!

Outport is a Laravel package that helps you create SQLite databases from arbitrary Laravel collections.
Just pass in your collections and set your table names.

Outport will:

+ Decide the best way to create tables for your collections
+ Create a SQLite database and migrate those tables
+ Insert data from your collection in chunks
+ Create indexes if asked

A simple *go()* call will wrap it all together and spit out the path to your SQLite file, complete with the tables and indexes you want. 

##Why would you want to output SQLite?

SQLite is a very compact format, therefore it is widely used inside mobile applications.

I have created this package to help facilitate data transportation between my Laravel back-end and SQLite enabled mobile app.
So if you need to pre-populate fresh copies of your app and/or push updates to existing copies through your Laravel back-end, this package will help you do that.

Suggestions and pull requests are welcome!