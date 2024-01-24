<?php

namespace App\Models\Sales;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'product_id',
        'unit_price',
        'quantity',
        'quantity_left',

        'unit_amount',
        'weight',
        'created_at',
        'updated_at'
    ];


    public function product(){
        return $this->belongsTo(Product::class);
    }


}
