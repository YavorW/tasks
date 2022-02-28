<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function getTypeAttribute() {
        return str_replace('App\\Models\\', '', $this->model_type);
    }
}
