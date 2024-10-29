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

echo "}
";
