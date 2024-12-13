<?php

namespace LaraBuild\Controllers;

use App\Http\Controllers\Controller;
use LaraBuild\Models\LaraMigration;
use LaraBuild\Models\LaraMigrationColumn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LaraMigrationController extends Controller
{
    public function index()
    {
        $datas = LaraMigration::paginate(10);

        return view('lara-migration.manage', compact('datas'));
    }

    public function create()
    {
        $existingMigrations = LaraMigration::all();
        return view('lara-migration.create', compact('existingMigrations'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'table_name' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (Str::singular($value) === $value) {
                        $fail("The Table Name must be in plural form.");
                    }

                    if (!preg_match('/^[a-z0-9_]+$/', $value) || Str::snake($value) !== $value) {
                        $fail("The Table Name must be in snake_case.");
                    }
                },
            ]
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', $validator->errors()->first());
        }

        $migration = LaraMigration::create(['table_name' => strtolower($request->table_name)]);

        foreach ($request->column as $column) {
            LaraMigrationColumn::create([
                'lara_migration_id' => $migration->id,
                'name' => $column['name'],
                'type' => $column['type'],
                'additional' => $column['additional_integer'] ?? $column['additional_foreign'] ?? null,
                'is_nullable' => $column['is_nullable'] ?? 0,
            ]);
        }

        return redirect()->route('lara-migration.index')
            ->with('success', "Migration Successfully Created");
    }


    public function edit($id)
    {
        $data = LaraMigration::with('columns')->findOrFail($id);
        $existingMigrations = LaraMigration::all();

        return view('lara-migration.edit', compact('data', 'existingMigrations'));
    }

    public function update(Request $request, $id)
    {
        $migration = LaraMigration::findOrFail($id);
        $migration->update(['table_name' => $request->table_name]);

        LaraMigrationColumn::where('lara_migration_id', $migration->id)->delete();

        foreach ($request->column as $column) {
            LaraMigrationColumn::create([
                'lara_migration_id' => $migration->id,
                'name' => $column['name'],
                'type' => $column['type'],
                'additional' => $column['additional_integer'] ?? $column['additional_foreign'],
                'is_nullable' => $column['is_nullable'] ?? 0,
            ]);
        }

        return redirect()->route('lara-migration.index')
            ->with('success', "Migration Successfully Updated");
    }

    public function destroy($id)
    {
        $laraMigration = LaraMigration::findOrFail($id);

        $existingTimestamp = Carbon::parse($laraMigration->generated_at)->format('Y_m_d_His');
        $existingPath = database_path("migrations/{$existingTimestamp}_create_{$laraMigration->table_name}_table.php");

        if (file_exists($existingPath)) unlink($existingPath);

        LaraMigrationColumn::where('lara_migration_id', $laraMigration->id)->delete();
        $laraMigration->delete();

        return response()->json(['success' => true]);
    }

    public function generate(Request $request)
    {
        $data = LaraMigration::with('columns')->findOrFail($request->id);

        $existingTimestamp = Carbon::parse($data->generated_at)->format('Y_m_d_His');
        $existingPath = database_path("migrations/{$existingTimestamp}_create_{$data->table_name}_table.php");

        $migrationFiles = scandir(database_path('migrations'));
        $tableName = $data->table_name;

        foreach ($migrationFiles as $file) {
            if (str_contains($file, "create_{$tableName}_table")) {
                $filePath = database_path("migrations/{$file}");

                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                break;
            }
        }

        $timestamp = date('Y_m_d_His');
        $fileName = "{$timestamp}_create_{$data->table_name}_table.php";
        $filePath = base_path("database/migrations/{$fileName}");

        File::put($filePath, view('stubs.migration', compact('data'))->render());

        $data->update(['generated_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function migrate()
    {
        Artisan::call('migrate:fresh-exclude');
        return response()->json(['success' => true]);
    }
}
