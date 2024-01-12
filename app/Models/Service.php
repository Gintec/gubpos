<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

      protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'customer');
    }

    public function parts()
    {
        return $this->hasMany(serviceparts::class, 'service_id', 'id');
    }

    public function questions()
    {
        return $this->hasMany(servicequestions::class, 'service_id', 'id');
    }
}
