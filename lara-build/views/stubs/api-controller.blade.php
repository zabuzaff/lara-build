<?php

echo "<?php

namespace App\Http\Controllers;

use App\Models\\" . Str::studly(Str::singular($data->table_name)) . ";
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
";

$foreignModels = [];
foreach ($data->columns as $column) {
    if ($column->type == 'foreign') {
        $foreignModel = Str::studly(Str::singular($column->additional));
        $foreignModels[] = "use App\Models\\$foreignModel;";
    }
}
echo implode("\n", array_unique($foreignModels)) . "\n";

echo "
class " . Str::studly(Str::singular($data->table_name)) . "ApiController extends BaseApiController
{
    public function index()
    {
        DB::beginTransaction();
        try {
            \$data = " . Str::studly(Str::singular($data->table_name)) . "::all();

            DB::commit();
            return \$this->success(\$data, '" . Str::studly(Str::plural($data->table_name)) . " fetched successfully.');
        } catch (\\Exception \$e) {
            DB::rollBack();
            return \$this->error('An error occurred while fetching " . Str::plural($data->table_name) . ": ' . \$e->getMessage());
        }
    }

    public function show(\$id)
    {
        DB::beginTransaction();
        try {
            \$data = " . Str::studly(Str::singular($data->table_name)) . "::findOrFail(\$id);

            DB::commit();
            return \$this->success(\$data, '" . Str::studly(Str::singular($data->table_name)) . " fetched successfully.');
        } catch (\\Exception \$e) {
            DB::rollBack();
            return \$this->error('An error occurred while fetching the " . Str::singular($data->table_name) . ": ' . \$e->getMessage());
        }
    }

    public function store(Request \$request)
    {
        DB::beginTransaction();
        try {
            \$data = " . Str::studly(Str::singular($data->table_name)) . "::create(\$request->all());

            DB::commit();
            return \$this->success(\$data, '" . Str::studly(Str::singular($data->table_name)) . " successfully created.');
        } catch (\\Exception \$e) {
            DB::rollBack();
            return \$this->error('An error occurred while creating the " . Str::singular($data->table_name) . ": ' . \$e->getMessage());
        }
    }

    public function update(Request \$request, \$id)
    {
        DB::beginTransaction();
        try {
            \$data = " . Str::studly(Str::singular($data->table_name)) . "::findOrFail(\$id);
            \$data->update(\$request->all());

            DB::commit();
            return \$this->success(\$data, '" . Str::studly(Str::singular($data->table_name)) . " successfully updated.');
        } catch (\\Exception \$e) {
            DB::rollBack();
            return \$this->error('An error occurred while updating the " . Str::singular($data->table_name) . ": ' . \$e->getMessage());
        }
    }

    public function destroy(\$id)
    {
        DB::beginTransaction();
        try {
            " . Str::studly(Str::singular($data->table_name)) . "::findOrFail(\$id)->delete();

            DB::commit();
            return \$this->success([], '" . Str::studly(Str::singular($data->table_name)) . " successfully deleted.');
        } catch (\\Exception \$e) {
            DB::rollBack();
            return \$this->error('An error occurred while deleting the " . Str::singular($data->table_name) . ": ' . \$e->getMessage());
        }
    }
}
";
