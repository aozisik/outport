<?php

namespace Aozisik\Outport;

use DB;
use Config;
use Schema;
use Exception;
use Illuminate\Support\Collection;

class Outport
{
    protected $db;
    protected $path;
    protected $schema;
    protected $connectionId;

    public function __construct($path = null)
    {
        $this->models = collect([]);
        $this->path = $path;
        $this->createSqliteConnection();
    }

    protected function createSqliteFile()
    {
        if (is_null($this->path)) {
            $this->path = storage_path('app');
        }
        if (!file_exists($this->path)) {
            touch($this->path);
        }
    }

    protected function createSqliteConnection()
    {
        $this->createSqliteFile();
        $this->connectionId = uniqid('outport_');

        Config::set('database.connections.' . $this->connectionId, [
            'driver' => 'sqlite',
            'database' => $this->path,
            'prefix' => '',
        ]);

        $this->db = DB::connection($this->connectionId);
        $this->schema = Schema::connection($this->connectionId);
    }

    protected function disconnect()
    {
        DB::disconnect($this->connectionId);
    }


    public function tableFromCollection($table, Collection $collection)
    {
        if ($collection->count()) {
            $this->models->put($table, $collection);
        }
        return $this;
    }

    public function go()
    {
        $this->models->each(function ($model, $table) {

        });
    }
}