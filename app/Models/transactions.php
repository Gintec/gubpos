<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transactions extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function productionjobs()
    {
        return $this->belongsTo(productionjobs::class, 'id', 'production_id');
    }

    public function accounthead()
    {
        return $this->hasOne(accountheads::class, 'id', 'account_head');
    }

    public function customer()
    {
        return $this->hasOne(User::class, 'id', 'from');
    }
}
