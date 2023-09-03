<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Procurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'count_id',
        'code',
        'supplier_id',
        'input_id',
        'procurement_date',
        'expected_weight',
        'expected_bags',
        'location',
        'note',
        'status',
        'next',
        'user_id',
    ];

    public function supplier():BelongsTo{
        return $this->belongsTo(Supplier::class);
    }

    public function input():BelongsTo{
        return $this->belongsTo(Input::class);
    }


    public function security(): HasOne
    {
        return $this->hasOne(Security::class);
    }

    protected function procurementDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d-m-Y'),
            set: fn (string $value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

}
