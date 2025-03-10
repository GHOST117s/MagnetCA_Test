<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        "name",
        "description",
        "price",
        "GST",
        "state_id",
        'final_price'

    ];


    public function state()
    {
        return $this->belongsTo(States::class, 'state_id');
    }

    

    public function calculateFinalPrice()
    {
        $price = $this->price ?? 0;
        $gstAmount = ($this->GST / 100) * $price; // GST as percentage
        $stateCharge = $this->state?->charges ?? 0;
        $deliveryCharge = $this->state?->delivery_charges ?? 0;

        return $price + $gstAmount + $stateCharge + $deliveryCharge;
    }
}
