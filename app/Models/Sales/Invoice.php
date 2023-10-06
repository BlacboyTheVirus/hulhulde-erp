<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'count_id',
        'code',
        'date',
        'sub_total',
        'discount',
        'grand_total',
        'amount_paid',
        'amount_due',
        'note',
        'payment_status',
        'created_by',
    ];



    public function invoiceitems(){
        return $this->hasMany(InvoiceItem::class);
    }


    public function customer(){
        return $this->belongsTo(Customer::class);
    }

}
