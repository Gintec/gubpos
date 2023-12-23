<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class spareparts extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function stock()
    {
        return $this->hasOne(sstock::class, 'sparepart', 'id');
    }
}
