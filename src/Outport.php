<?php

namespace Aozisik\Outport;

use Illuminate\Support\Collection;

class Outport
{
    protected $indexes;
    protected $connection;
    protected $collections;

    public function __construct($path = null)
    {
        $this->indexes = Collection::make([]);
        $this->collections = Collection::make([]);
        $this->connection = new Connection($path);
    }

    public function table($table, Collection $collection, array $indexes = [])
    {
        if ($collection->count()) {
            $this->collections->put($table, $collection);
            $this->indexes->put($table, $indexes);
        }
        return $this;
    }

    public function go()
    {
        $this->connection->open();

        foreach ($this->collections as $table => $collection) {
            // Migrate sqlite table
            Schema::fromCollection($collection)->create(
                $this->connection,
                $table,
                $this->indexes->get($table)
            );
            // Insert data in chunks
            $collection->chunk(30)->each(function ($chunk) use ($table) {
                $this->connection->insert($table, $chunk);
            });
        }

        $this->connection->close();
        return $this->connection->path;
    }
}