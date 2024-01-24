<?php

namespace App\Models;

use App\Models\Sales\Invoice;
use App\Models\Sales\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'count_id',
        'code',
        'name',
        'phone',
        'address',
        'invoice_due',
        'wallet',
        'created_by'
    ];


    public function invoices(){
        return $this->hasMany(Invoice::class);
    }

    public function invoice_items(){
        return $this->hasManyThrough(InvoiceItem::class, Invoice::class);
    }



}
