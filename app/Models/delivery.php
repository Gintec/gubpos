<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class delivery extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function settings()
    {
        return $this->belongsTo(settings::class, 'id', 'setting_id');
    }


    public function transaction()
    {
        return $this->hasOne(transactions::class, 'id', 'invoice_no');
    }

    public function Customer()
    {
        return $this->hasOne(User::class, 'id', 'customer');
    }

    public function DeliveredBy()
    {
        return $this->hasOne(User::class, 'id', 'deliveredBy');
    }


}
