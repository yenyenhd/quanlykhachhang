<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function order()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }
    public function loyalty()
    {
        return $this->hasOne(Loyalty::class, 'customer_id');
    }
    public function card()
    {
        return $this->belongsToMany(Card::class, 'customer_cards', 'customer_id', 'card_id')->withTimestamps();
    }
}
