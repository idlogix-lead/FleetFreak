<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AuthenticatableModel extends Authenticatable
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            $tableName = $model->getTable();
            $primaryKey = $model->getKeyName();
            $sequenceName = "{$tableName}_{$primaryKey}_seq"; // Adjust as needed

            $model->getConnection()->statement("SELECT setval('{$sequenceName}', (SELECT MAX({$primaryKey}) from \"{$tableName}\"));");
        });
    }
}
