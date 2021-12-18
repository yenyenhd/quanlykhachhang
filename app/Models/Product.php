<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;


class Product extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'products';

    const STATUS_PUBLIC = 1;
    const STATUS_PRIVATE = 0;

    protected $active = [
        1 => [
            'name' => 'Hiển thị',
            'class' => 'badge-danger'
        ],
        0 => [
            'name' => 'Ẩn',
            'class' => 'badge-secondary'
        ]
    ];
     protected $product_hot = [
        1 => [
            'name' => 'Nổi bật',
            'class' => 'badge-success'
        ],
        0 => [
            'name' => 'Không',
            'class' => 'badge-secondary'
        ]
    ];
    public function getStatus()
    {
        return Arr::get($this->active, $this->status, '[N\A]');
    }
    public function getHot()
    {
        return arr::get($this->product_hot, $this->hot, '[N\A]');
    }
    public function category()
    {
        return $this->belongsTo(CategoryProduct::class, 'category_id');
    }
   
    public function stores()
    {
        return $this->belongsToMany(Store::class, 'inventories', 'product_id', 'store_id')->withTimestamps();
    }
    
    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'product_id', 'id');
    }
}
