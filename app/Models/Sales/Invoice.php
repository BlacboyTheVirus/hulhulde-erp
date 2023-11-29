<?php

namespace App\Models\Sales;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'note',
        'amount_due',
        'amount_paid',
        'payment_status',
        'user_id',
    ];



    public function invoiceitems():HasMany{
        return $this->hasMany(InvoiceItem::class);
    }


    public function payments():HasMany{
        return $this->hasMany(Payment::class);
    }


    public function stores():HasMany{
        return $this->hasMany(Store::class);
    }



    public function customer():BelongsTo{
        return $this->belongsTo(Customer::class);
    }


    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d-m-Y'),
            set: fn (string $value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

}
