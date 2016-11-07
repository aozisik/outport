⚓️ Outport [![Build Status](https://api.travis-ci.org/aozisik/outport.svg?branch=master)](https://travis-ci.org/aozisik/outport)
========


Just pass in a Laravel collection and Outport will turn that into a SQLite database.
    
##How do I use it?

First, you should require this package through composer:

    composer require aozisik/outport

Then it is as simple as this:

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
        ->table('books', $books, ['author'])
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

At the end you will get a path to the resulting SQLite file, containing all the data you have just passed in. 

##Why would you want that?

Well, SQLite is a very compact format and it is very useful especially for mobile applications.

If you have a mobile app that needs to work offline and you need to pre-populate that application with data, Outport can help you by generating the initial SQLite file using whatever data you wish to put in.

You can pull in data from your Eloquent models, from a third-party service, you name it. Just pass in your collection and use the SQLite file wherever you like.

You can also do a more sophisticated implementation where you keep generated SQLite files as versions of your app database and create an endpoint to access these files. Now you have turned your Laravel app to an update server! 🙌

Suggestions and pull requests are welcome!