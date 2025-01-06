<?php

echo "<?php

namespace App\Http\Controllers;

use App\Models\\" . Str::studly(Str::singular($data->table_name)) . ";
use Illuminate\Http\Request;
";

// Collect unique foreign models
$foreignModels = [];
foreach ($data->columns as $column) {
    if ($column->type == 'foreign') {
        $foreignModel = Str::studly(Str::singular($column->additional));
        $foreignModels[] = "use App\Models\\$foreignModel;";
    }
}
echo implode("\n", array_unique($foreignModels)) . "\n";

echo "
class " . Str::studly(Str::singular($data->table_name)) . "Controller extends Controller
{
    public function index()
    {
        \$datas = " . Str::studly(Str::singular($data->table_name)) . "::paginate();
        return view('" . Str::kebab(Str::singular(trim($data->table_name))) . ".manage', compact('datas'));
    }

    public function edit(\$id)
    {
        \$data = " . Str::studly(Str::singular($data->table_name)) . "::findOrFail(\$id);
";

$foreignEditVariables = []; // Array for holding foreign variables for compact

foreach ($data->columns as $column) {
    if ($column->type == 'foreign') {
        $foreignVariableName = Str::kebab(Str::plural($column->additional));
        $foreignModel = Str::studly(Str::singular($column->additional));
        echo "        \$$foreignVariableName = $foreignModel::all();" . PHP_EOL;
        $foreignEditVariables[] = $foreignVariableName;
    }
}

echo "
        return view('" . Str::kebab(Str::singular(trim($data->table_name))) . ".edit', compact('data'";

if (!empty($foreignEditVariables)) {
    echo ", '" . implode("', '", $foreignEditVariables) . "'";
}

echo "));
    }

    public function destroy(\$id)
    {
        " . Str::studly(Str::singular($data->table_name)) . "::findOrFail(\$id)->delete();
        return response()->json(['success' => true]);
    }

    public function create()
    {
";

$foreignVariables = []; // Array for holding foreign variables for compact in create method

foreach ($data->columns as $column) {
    if ($column->type == 'foreign') {
        $foreignVariableName = Str::kebab(Str::plural($column->additional));
        $foreignModel = Str::studly(Str::singular($column->additional));
        echo "        \$$foreignVariableName = $foreignModel::all();" . PHP_EOL;
        $foreignVariables[] = $foreignVariableName;
    }
}

echo "
        return view('" . Str::kebab(Str::singular(trim($data->table_name))) . ".create'";

if (!empty($foreignVariables)) {
    echo ", compact('" . implode("', '", $foreignVariables) . "')";
}

echo ");
    }

    public function store(Request \$request)
    {
        " . Str::studly(Str::singular($data->table_name)) . "::create(\$request->all());
        return redirect()->route('" . Str::kebab(Str::singular(trim($data->table_name))) . ".index')
            ->with('success', '" . Str::studly(Str::singular($data->table_name)) . " Successfully Added');
    }

    public function update(Request \$request, \$id)
    {
        " . Str::studly(Str::singular($data->table_name)) . "::findOrFail(\$id)->update(\$request->all());
        return redirect()->route('" . Str::kebab(Str::singular(trim($data->table_name))) . ".index')
            ->with('success', '" . Str::studly(Str::singular($data->table_name)) . " Successfully Updated');
    }

    public function show(\$id)
    {
        \$data = " . Str::studly(Str::singular($data->table_name)) . "::findOrFail(\$id);
        return view('" . Str::kebab(Str::singular(trim($data->table_name))) . ".show', compact('data'));
    }
}
";
