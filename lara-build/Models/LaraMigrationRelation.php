<?php

namespace LaraBuild\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaraMigrationRelation extends Model
{
    use HasFactory;

    protected $fillable = [
        'lara_migration_id',
        'type',
        'foreign_table'
    ];

    public function laraMigration()
    {
        return $this->belongsTo(LaraMigration::class());
    }
}
