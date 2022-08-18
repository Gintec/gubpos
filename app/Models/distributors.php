<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class distributors extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function settings()
    {
        return $this->belongsTo(settings::class, 'id', 'setting_id');
    }

}
