<?php

namespace LaraBuild\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaraMigrationColumn extends Model
{
    use HasFactory;

    protected $fillable = [
        'lara_migration_id',
        'name',
        'type',
        'additional',
        'is_nullable'
    ];

    public function laraMigration()
    {
        return $this->belongsTo(LaraMigration::class());
    }
}
