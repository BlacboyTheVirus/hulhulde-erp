<?php

namespace App\Models\Sales;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;

    protected $table = 'invoice_stores';

    protected $fillable = [
        'invoice_id',
        'released_date',
        'product_id',
        'quantity',
        'weight',
        'released_by',
        'user_id',
    ];



    public function invoice():BelongsTo{
        return $this->belongsTo(Invoice::class);
    }


    protected function releasedDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d-m-Y'),
            set: fn (string $value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }
}
