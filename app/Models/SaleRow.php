<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleRow extends Model
{
    use HasFactory;
    protected $table = 'sale_rows';
    public $timestamps = false;

    public function sale ()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product ()
    {
        return $this->belongsTo(Product::class);
    }
}
