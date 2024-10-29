<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lara_migration_columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lara_migration_id');
            $table->string('name');
            $table->string('type');
            $table->string('additional')->nullable();
            $table->boolean('is_nullable')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lara_migration_columns');
    }
};
