<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{

    protected $fillable = ['product_id', 'state_id', 'final_price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function state()
    {
        return $this->belongsTo(States::class);
    }
    

}
