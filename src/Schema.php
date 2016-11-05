<?php

namespace Aozisik\Outport;

use Schema as EloquentSchema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;

class Schema
{
    protected $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public static function fromCollection(Collection $collection)
    {
        $fields = [];
        $sample = $collection->first();

        $typeChecks = [
            'text' => 'is_string',
            'integer' => 'is_int',
            'boolean' => 'is_bool',
            'double' => 'is_double'
        ];

        foreach ($sample as $field => $value) {
            $type = 'text';
            foreach ($typeChecks as $type => $checkMethod) {
                if (call_user_func($checkMethod, $value)) {
                    break;
                }
            }
            // TODO: Handle cases where the value is not a string, integer, boolean or a double
            $fields[$field] = $type;
        }

        return new Schema($fields);
    }

    public function create(Connection $connection, $table)
    {
        EloquentSchema::connection($connection->id)->create($table, function (Blueprint $table) {
            foreach ($this->fields as $field => $type) {
                $table->$type($field)->nullable();
            }
        });
    }
}