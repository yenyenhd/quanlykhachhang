<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;


class Order extends Model
{
    use HasFactory;
    protected $guarded = [];
   
   
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function product()
    {
        return $this->belongsToMany(Product::class, 'order_details', 'order_code', 'product_id')->withTimestamps();
    }
}
