<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

      protected $fillable = ['customer', 'date', 'category', 'description', 'status', 'amount'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'customer');
    }
}
