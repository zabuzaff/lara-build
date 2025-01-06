<?php

namespace LaraBuild\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaraMigration extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_name',
        'generated_at'
    ];

    public function columns()
    {
        return $this->hasMany(LaraMigrationColumn::class);
    }

    public function relations()
    {
        return $this->hasMany(LaraMigrationRelation::class);
    }
}
