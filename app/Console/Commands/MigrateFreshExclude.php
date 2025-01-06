<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateFreshExclude extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:fresh-exclude';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrate:fresh but exclude specified tables';

    /**
     * Tables to exclude from the drop operation.
     *
     * @var array
     */
    protected $excludedTables = [
        'failed_jobs',
        'lara_migration_columns',
        'lara_migration_relations',
        'lara_migrations',
        'migrations',
        'password_reset_tokens',
        'users'
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Dropping all tables except excluded ones...');

        $tables = DB::select('SHOW TABLES');
        $database = DB::getDatabaseName();

        foreach ($tables as $table) {
            $tableName = $table->{"Tables_in_{$database}"};

            if (!in_array($tableName, $this->excludedTables)) {
                Schema::drop($tableName);
                $this->info("Dropped table: $tableName");
            } else {
                $this->info("Excluded table: $tableName");
            }
        }

        $this->info('Clearing migration data for dropped tables...');

        $migrationRecords = DB::table('migrations')->get();

        foreach ($migrationRecords as $migrationRecord) {
            $migrationName = $migrationRecord->migration;

            $isExcluded = false;
            foreach ($this->excludedTables as $excludedTable) {
                if (str_contains($migrationName, $excludedTable)) {
                    $isExcluded = true;
                    break;
                }
            }

            if (!$isExcluded) {
                DB::table('migrations')->where('migration', $migrationName)->delete();
                $this->info("Cleared migration data for: $migrationName");
            }
        }

        $this->info('Running migrations...');
        Artisan::call('migrate', ['--force' => true]);

        $this->info('Migration process completed!');
    }
}
