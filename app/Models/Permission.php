<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    
    protected $fillable = ['name', 'feature_id'];

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}