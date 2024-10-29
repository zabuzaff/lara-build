<?php

namespace App\Http\Controllers;

use App\Models\LaraMigration;
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
            'lara_migrations',
            'migrations',
            'password_reset_tokens',
            'personal_access_tokens',
            'users'
        ];

        $tableNames = array_map(fn($table) => $table->{"Tables_in_post_test"}, Schema::getAllTables());
        $datas = array_filter($tableNames, fn($tableName) => !in_array($tableName, $excludedTables));

        return view('lara-build.generate-crud', compact('datas'));
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
            $this->generateController($request->table);
            $this->configureRoute($request->table);
        }

        return response()->json(['success' => true]);
    }

    private function generateModel($table)
    {
        $data = LaraMigration::with('columns')->where('table_name', $table)->firstOrFail();

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

        $filePath = base_path('routes/web.php');
        $newRoute = "\t\t'" . Str::kebab(Str::singular(trim($data->table_name))) . "' => App\Http\Controllers\\" . Str::studly(Str::singular($data->table_name)) . "Controller::class,\n";

        $fileContent = file_get_contents($filePath);

        $startComment = '//start-generated-resources';
        $endComment = '//end-generated-resources';

        $startPosition = strpos($fileContent, $startComment);
        $endPosition = strpos($fileContent, $endComment);

        if ($startPosition !== false && $endPosition !== false && $endPosition > $startPosition) {
            $generatedContent = substr($fileContent, $startPosition + strlen($startComment), $endPosition - ($startPosition + strlen($startComment)));

            if (strpos($generatedContent, trim($newRoute)) === false) {
                $generatedContent .= "\n        $newRoute";

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
            "name" => Str::kebab(Str::singular(trim($table))),
            "icon" => "fa-bolt",
        ];

        $duplicateFound = false;
        foreach ($existingData as $item) {
            if ($item['name'] === $newEntry['name']) {
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
