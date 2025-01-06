<?php

namespace LaraBuild\Controllers;

use App\Http\Controllers\Controller;
use LaraBuild\Models\LaraMigration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class LaraBuildController extends Controller
{
    public function generateCrud()
    {
        $excludedTables = [
            'failed_jobs',
            'lara_migration_columns',
            'lara_migration_relations',
            'lara_migrations',
            'migrations',
            'password_reset_tokens',
            'personal_access_tokens',
            'users'
        ];

        $tableNames = array_map(fn($table) => $table->{"Tables_in_" . env('DB_DATABASE')}, Schema::getAllTables());
        $datas = array_filter($tableNames, fn($tableName) => !in_array($tableName, $excludedTables));

        return view('lara-build.generate-crud', compact('datas'));
    }

    public function bulkGenerate(Request $request)
    {
        foreach ($request->datas as $data) {
            $configs = new Request();
            $configs->merge($data);
            $this->generate($configs);
        }

        return response()->json(['success' => true]);
    }

    public function generate(Request $request)
    {
        if ($request->model == 'on') {
            $this->generateModel($request->table);
        }

        if ($request->view == 'on') {
            $this->generateManage($request->table);
            $this->generateCreate($request->table);
            $this->generateEdit($request->table);
            $this->generateShow($request->table);
            $this->generateSidenav($request->table);
        }

        if ($request->controller == 'on') {
            $this->generateModel($request->table);
            $this->generateController($request->table);
            $this->configureRoute($request->table);
        }

        if ($request->api == 'on') {
            $this->generateModel($request->table);
            $this->generateApiController($request->table);
            $this->configureRouteApi($request->table);
        }

        return response()->json(['success' => true]);
    }

    private function generateModel($table)
    {
        $data = LaraMigration::with('columns', 'relations')->where('table_name', $table)->firstOrFail();

        $fileName = Str::studly(Str::singular($data->table_name)) . ".php";
        $filePath = app_path("Models/{$fileName}");

        File::put($filePath, view('stubs.model', compact('data'))->render());
    }

    private function generateController($table)
    {
        $data = LaraMigration::with('columns')->where('table_name', $table)->firstOrFail();

        $fileName = Str::studly(Str::singular($data->table_name)) . "Controller.php";
        $filePath = app_path("Http/Controllers/{$fileName}");

        File::put($filePath, view('stubs.controller', compact('data'))->render());
    }

    private function configureRoute($table)
    {
        $data = LaraMigration::with('columns')->where('table_name', $table)->firstOrFail();

        $filePath = base_path('routes/generated-web-resources.php');
        $newRoute = "Route::resource('" . Str::kebab(Str::singular(trim($data->table_name))) . "', "
            . "App\Http\Controllers\\" . Str::studly(Str::singular($data->table_name)) . "Controller::class);\n";

        $fileContent = file_get_contents($filePath);

        $startComment = '//start-generated-resources';
        $endComment = '//end-generated-resources';

        $startPosition = strpos($fileContent, $startComment);
        $endPosition = strpos($fileContent, $endComment);

        if ($startPosition !== false && $endPosition !== false && $endPosition > $startPosition) {
            $generatedContent = substr($fileContent, $startPosition + strlen($startComment), $endPosition - ($startPosition + strlen($startComment)));

            if (strpos($generatedContent, trim($newRoute)) === false) {
                $generatedContent .= "$newRoute";

                $fileContent = substr_replace(
                    $fileContent,
                    $generatedContent,
                    $startPosition + strlen($startComment),
                    $endPosition - ($startPosition + strlen($startComment))
                );

                file_put_contents($filePath, $fileContent);

                info("Route added successfully.");
            } else {
                info("Route already exists.");
            }
        } else {
            info("Comment markers not found or incorrectly placed in web.php.");
        }
    }

    private function generateApiController($table)
    {
        $data = LaraMigration::with('columns')->where('table_name', $table)->firstOrFail();

        $fileName = Str::studly(Str::singular($data->table_name)) . "ApiController.php";
        $filePath = app_path("Http/Controllers/{$fileName}");

        File::put($filePath, view('stubs.api-controller', compact('data'))->render());
    }

    private function configureRouteApi($table)
    {
        $data = LaraMigration::with('columns')->where('table_name', $table)->firstOrFail();

        $filePath = base_path('routes/generated-api-resources.php');
        $newRoute = "Route::resource('" . Str::kebab(Str::singular(trim($data->table_name))) . "', "
            . "App\Http\Controllers\\" . Str::studly(Str::singular($data->table_name)) . "ApiController::class);\n";

        $fileContent = file_get_contents($filePath);

        $startComment = '//start-generated-resources';
        $endComment = '//end-generated-resources';

        $startPosition = strpos($fileContent, $startComment);
        $endPosition = strpos($fileContent, $endComment);

        if ($startPosition !== false && $endPosition !== false && $endPosition > $startPosition) {
            $generatedContent = substr($fileContent, $startPosition + strlen($startComment), $endPosition - ($startPosition + strlen($startComment)));

            if (strpos($generatedContent, trim($newRoute)) === false) {
                $generatedContent .= "$newRoute";

                $fileContent = substr_replace(
                    $fileContent,
                    $generatedContent,
                    $startPosition + strlen($startComment),
                    $endPosition - ($startPosition + strlen($startComment))
                );

                file_put_contents($filePath, $fileContent);

                info("API Route added successfully.");
            } else {
                info("API Route already exists.");
            }
        } else {
            info("Comment markers not found or incorrectly placed in api.php.");
        }
    }

    private function generateManage($table)
    {
        $data = LaraMigration::with('columns')->where('table_name', $table)->firstOrFail();

        $filePath = resource_path("views/" . Str::kebab(Str::singular(trim($data->table_name))) . "/manage.blade.php");

        $directoryPath = dirname($filePath);

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        File::put($filePath, view('stubs.manage', compact('data'))->render());
    }

    private function generateCreate($table)
    {
        $data = LaraMigration::with('columns')->where('table_name', $table)->firstOrFail();

        $filePath = resource_path("views/" . Str::kebab(Str::singular(trim($data->table_name))) . "/create.blade.php");

        $directoryPath = dirname($filePath);

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }
        File::put($filePath, view('stubs.create', compact('data'))->render());
    }

    private function generateEdit($table)
    {
        $data = LaraMigration::with('columns')->where('table_name', $table)->firstOrFail();

        $filePath = resource_path("views/" . Str::kebab(Str::singular(trim($data->table_name))) . "/edit.blade.php");

        $directoryPath = dirname($filePath);

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        File::put($filePath, view('stubs.edit', compact('data'))->render());
    }

    private function generateShow($table)
    {
        $data = LaraMigration::with('columns')->where('table_name', $table)->firstOrFail();

        $filePath = resource_path("views/" . Str::kebab(Str::singular(trim($data->table_name))) . "/show.blade.php");

        $directoryPath = dirname($filePath);

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        File::put($filePath, view('stubs.show', compact('data'))->render());
    }

    private function generateSidenav($table)
    {
        $jsonFilePath = resource_path('views/layouts/crud.json');

        $existingData = [];

        if (File::exists($jsonFilePath)) {
            $existingData = json_decode(File::get($jsonFilePath), true);
        }

        $newEntry = [
            "route" => Str::kebab(Str::singular(trim($table))),
            "name" => Str::kebab(Str::singular(trim($table))),
            "icon" => "fa-bolt",
            "role" => ['admin']
        ];

        $duplicateFound = false;
        foreach ($existingData as $item) {
            if ($item['route'] === $newEntry['route']) {
                $duplicateFound = true;
                break;
            }
        }

        if (!$duplicateFound) {
            $existingData[] = $newEntry;

            File::put($jsonFilePath, json_encode($existingData, JSON_PRETTY_PRINT));
        }
    }
}
