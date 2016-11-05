<?php

namespace Aozisik\Outport;

use Illuminate\Support\Collection;

class Outport
{
    protected $connection;
    protected $collections;

    public function __construct($path = null)
    {
        $this->collections = collect([]);
        $this->connection = new Connection($path);
    }

    public function table($table, Collection $collection)
    {
        if ($collection->count()) {
            $this->collections->put($table, $collection);
        }
        return $this;
    }

    public function go()
    {
        $this->connection->open();

        $this->collections->each(function (Collection $collection, $table) {
            // Migrate sqlite table
            Schema::fromCollection($collection)->create(
                $this->connection,
                $table
            );
            // Insert data in chunks
            $collection->chunk(30)->each(function ($chunk) use ($table) {
                $this->connection->insert($table, $chunk);
            });
        });

        $this->connection->close();
        return $this->connection->path;
    }
}