<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $table = 'features';

    protected $fillable = ['name'];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}