<?php

namespace Aozisik\Outport;

use DB
use Config;
use Exception;
use Illuminate\Support\Collection;

class Connection
{
    public $id;
    public $path;
    protected $link;

    public function __construct($path = '')
    {
        $this->path = $path;
        $this->id = uniqid('outport_');
    }

    protected function getDefaultSqlitePath()
    {
        return storage_path('app/') . $this->id . '.sqlite';
    }

    protected function createSqliteFile()
    {
        if (is_null($this->path)) {
            $this->path = $this->getDefaultSqlitePath();
        }
        if (!file_exists($this->path)) {
            touch($this->path);
        }
    }

    public function open()
    {
        $this->createSqliteFile();

        Config::set('database.connections.' . $this->id, [
            'driver' => 'sqlite',
            'database' => $this->path,
            'prefix' => '',
        ]);

        $this->link = DB::connection($this->id);
    }

    public function close()
    {
        DB::disconnect($this->id);
    }

    public function insert($table, Collection $collection)
    {
        if (!$this->link) {
            throw new Exception("SQLite Connection not found");
        }
        $this->link->table($table)->insert($collection->toArray());
    }
}