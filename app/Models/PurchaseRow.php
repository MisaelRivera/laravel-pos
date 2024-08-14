<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRow extends Model
{
    use HasFactory;
    protected $table = 'purchase_rows';
    public $timestamps = false;

    public function purchase ()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product ()
    {
        return $this->belongsTo(Product::class);
    }
}
