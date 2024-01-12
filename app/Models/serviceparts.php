<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class serviceparts extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function sparepart()
    {
        return $this->hasOne(spareparts::class, 'id', 'part_id');
    }
}
