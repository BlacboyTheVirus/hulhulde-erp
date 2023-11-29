<?php

namespace App\Models;

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
        'created_by'
    ];


    public function invoices(){
        return $this->hasMany(Invoice::class);
    }

    public function invoice_items(){
        return $this->hasManyThrough(InvoiceItem::class, Invoice::class);
    }



}
