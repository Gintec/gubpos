<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class servicepartsupplies extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function part()
    {
        return $this->hasOne(spareparts::class, 'id', 'part_id');
    }

    public function suppliedBy()
    {
        return $this->hasOne(suppliers::class, 'id', 'supplier');
    }

}
