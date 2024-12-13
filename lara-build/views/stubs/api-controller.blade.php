<?php

echo "<?php

namespace App\Http\Controllers;

use App\Models\\" . Str::studly(Str::singular($data->table_name)) . ";
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
class " . Str::studly(Str::singular($data->table_name)) . "ApiController extends Controller
{
    public function index()
    {
        try {
            \$data = " . Str::studly(Str::singular($data->table_name)) . "::paginate(10);
            return response()->json([
                'success' => true,
                'message' => 'Data fetched successfully.',
                'data' => \$data,
            ], Response::HTTP_OK);
        } catch (\Exception \$e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching data.',
                'error' => \$e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(\$id)
    {
        try {
            \$data = " . Str::studly(Str::singular($data->table_name)) . "::findOrFail(\$id);
            return response()->json([
                'success' => true,
                'message' => 'Data fetched successfully.',
                'data' => \$data,
            ], Response::HTTP_OK);
        } catch (\Exception \$e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the data.',
                'error' => \$e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request \$request)
    {
        DB::beginTransaction();
        try {
            \$data = " . Str::studly(Str::singular($data->table_name)) . "::create(\$request->all());
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => '" . Str::studly(Str::singular($data->table_name)) . " successfully created.',
                'data' => \$data,
            ], Response::HTTP_CREATED);
        } catch (\Exception \$e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the resource.',
                'error' => \$e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request \$request, \$id)
    {
        DB::beginTransaction();
        try {
            \$data = " . Str::studly(Str::singular($data->table_name)) . "::findOrFail(\$id);
            \$data->update(\$request->all());
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => '" . Str::studly(Str::singular($data->table_name)) . " successfully updated.',
                'data' => \$data,
            ], Response::HTTP_OK);
        } catch (\Exception \$e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the resource.',
                'error' => \$e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(\$id)
    {
        DB::beginTransaction();
        try {
            " . Str::studly(Str::singular($data->table_name)) . "::findOrFail(\$id)->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => '" . Str::studly(Str::singular($data->table_name)) . " successfully deleted.',
            ], Response::HTTP_OK);
        } catch (\Exception \$e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the resource.',
                'error' => \$e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
";
