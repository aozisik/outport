<?php

namespace Aozisik\Outport;

use DB;
use Config;
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

    public function tableFromCollection($table, Collection $collection)
    {
        if ($collection->count()) {
            $this->collections->put($table, $collection);
        }
        return $this;
    }

    public function go()
    {
        $this->connection->open();

        $this->collections->each(function ($collection, $table) {
            // Migrate sqlite table
            Schema::fromCollection($collection)->create(
                $this->connection,
                $table
            );
        });

        $this->connection->close();
    }
}