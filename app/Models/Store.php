<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $guarded = [];
   
    public function user()
    {
        return $this->hasMany(User::class, 'store_id', 'id');
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'inventories', 'store_id', 'product_id')->withTimestamps();
    }
}
