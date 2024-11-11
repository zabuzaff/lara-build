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
    protected $description = 'Run migrate:fresh but exclude specified tables';

    protected $excludedTables = [
        'failed_jobs',
        'lara_migration_columns',
        'lara_migrations',
        'migrations',
        'password_reset_tokens',
        'users'
    ];

    public function handle()
    {
        $this->info('Dropping all tables except excluded ones...');

        $tables = DB::select('SHOW TABLES');

        foreach ($tables as $table) {
            $tableName = array_values((array) $table)[0];
            if (!in_array($tableName, $this->excludedTables)) {
                Schema::drop($tableName);
                $this->info("Dropped table: $tableName");
            } else {
                $this->info("Excluded table: $tableName");
            }
        }

        Artisan::call('migrate', ['--force' => true]);

        $this->info('Migration!');
    }
}
