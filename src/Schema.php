<?php

namespace Aozisik\Outport;

use Illuminate\Support\Collection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema as EloquentSchema;

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

    public function create(Connection $connection, $table, array $indexes = [])
    {
        // Remove id field as it is added by default
        $this->fields = array_except($this->fields, 'id');

        EloquentSchema::connection($connection->id)->create($table, function (Blueprint $table) use ($indexes) {
            $table->increments('id');
            foreach ($this->fields as $field => $type) {
                $table->$type($field)->nullable();
            }
            foreach ($indexes as $index) {
                $table->index($index);
            }
        });
    }
}