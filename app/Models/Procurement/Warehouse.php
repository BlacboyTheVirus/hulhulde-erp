<?php

namespace App\Models\Procurement;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'count_id',
        'code',
        'procurement_id',

        'receipt_date',
        'bags',
        'weight',
        'received_by',

        'note',
        'user_id',
    ];


    public function procurement():BelongsTo
    {
        return $this->belongsTo(Procurement::class);
    }


    protected function receiptDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d-m-Y'),
            set: fn (string $value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }



}
