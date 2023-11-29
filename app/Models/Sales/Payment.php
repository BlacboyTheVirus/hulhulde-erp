<?php

namespace App\Models\Sales;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'invoice_payments';

    protected $fillable = [
        'count_id',
        'code',
        'invoice_id',
        'payment_date',
        'payment_type',
        'amount',
        'note',
        'user_id',
    ];


    public function invoice():BelongsTo{
        return $this->belongsTo(Invoice::class);
    }



    protected function paymentDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d-m-Y'),
            set: fn (string $value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

}
