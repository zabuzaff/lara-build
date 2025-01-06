<?php
echo "
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class " . Str::studly(Str::singular($data->table_name)) . " extends Model
{
    use HasFactory;

    protected \$fillable = [
";

foreach ($data->columns as $column) {
    echo "        '" . $column->name . "',\n";
}

echo "    ];\n\n";

foreach ($data->columns as $column) {
    if ($column->type == 'foreign') {
        $relatedModel = Str::studly(Str::singular($column->additional));
        $functionName = Str::camel(Str::singular($column->additional));

        echo "    public function $functionName()\n";
        echo "    {\n";
        echo "        return \$this->belongsTo($relatedModel::class);\n";
        echo "    }\n\n";
    }
}

foreach ($data->relations as $relation) {
    $relatedModel = Str::studly(Str::singular($relation->foreign_table));
    if ($relation->type == 'hasOne') {
        $functionName = Str::camel(Str::singular($relation->foreign_table));

        echo "    public function $functionName()\n";
        echo "    {\n";
        echo "        return \$this->hasOne($relatedModel::class);\n";
        echo "    }\n\n";
    } else {
        $functionName = Str::camel(Str::plural($relation->foreign_table));

        echo "    public function $functionName()\n";
        echo "    {\n";
        echo "        return \$this->hasMany($relatedModel::class);\n";
        echo "    }\n\n";
    }
}

echo "}
";
