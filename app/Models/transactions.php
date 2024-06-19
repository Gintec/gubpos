<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transactions extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function accounthead()
    {
        return $this->hasOne(accountheads::class, 'id', 'account_head');
    }

    public function delivery()
    {
        return $this->hasOne(delivery::class, 'invoice_no', 'id');
    }

    public function customer()
    {
        return $this->hasOne(User::class, 'id', 'from');
    }

    public function productSales()
    {
        return $this->hasOne(product_sales::class, 'group_id', 'reference_no');
    }
}
