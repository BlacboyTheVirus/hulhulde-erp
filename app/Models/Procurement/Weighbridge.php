<?php

namespace App\Models\Procurement;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Weighbridge extends Model
{
    use HasFactory;

    protected $fillable = [
        'count_id',
        'code',
        'procurement_id',
        'first_date',
        'first_time',
        'first_weight',
        'second_date',
        'second_time',
        'second_weight',
        'weight',
        'bags',
        'operator',
        'note',
        'user_id',
    ];


    public function procurement(): BelongsTo
    {
        return $this->belongsTo(Procurement::class);
    }

    protected function firstDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d-m-Y'),
            set: fn (string $value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

    protected function secondDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d-m-Y'),
            set: fn (string $value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

}
