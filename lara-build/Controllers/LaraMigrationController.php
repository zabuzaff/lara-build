<?php

namespace LaraBuild\Controllers;

use App\Http\Controllers\Controller;
use LaraBuild\Models\LaraMigration;
use LaraBuild\Models\LaraMigrationColumn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

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
        $migration = LaraMigration::create(['table_name' => $request->table_name]);

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

        LaraMigrationColumn::where('lara_migration_id', $laraMigration->id)->delete();
        $laraMigration->delete();

        return response()->json(['success' => true]);
    }

    public function generate(Request $request)
    {
        $data = LaraMigration::with('columns')->findOrFail($request->id);

        if ($data->generated_at != null) {
            $existingTimestamp = Carbon::parse($data->generated_at)->format('Y_m_d_His');
            $existingPath = database_path("migrations/{$existingTimestamp}_create_{$data->table_name}_table.php");

            if (file_exists($existingPath)) unlink($existingPath);
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
