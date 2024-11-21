<?php

use LaraBuild\Controllers\LaraBuildController;
use LaraBuild\Controllers\LaraMigrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'auth'], function () {
    Route::get('/generate-crud', [LaraBuildController::class, 'generateCrud'])->name('generate-crud');
    Route::post('/generate', [LaraBuildController::class, 'generate'])->name('lara-build.generate');
    Route::post('/bulk-generate', [LaraBuildController::class, 'bulkGenerate'])->name('lara-build.bulk-generate');
    Route::post('/lara-migration/generate', [LaraMigrationController::class, 'generate'])->name('lara-migration.generate');
    Route::post('/lara-migration/migrate', [LaraMigrationController::class, 'migrate'])->name('lara-migration.migrate');

    Route::resources([
        'lara-migration' => LaraMigrationController::class
    ]);
});
