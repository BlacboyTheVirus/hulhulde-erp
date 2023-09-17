<?php

namespace App\Models\Procurement;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quality extends Model
{
    use HasFactory;

    protected $fillable = [
        'count_id',
        'code',
        'procurement_id',
        'analysis_date',
        'moisture',
        'broken',
        'crackness',
        'immature',
        'red_grain',
        'green_grain',
        'yellow_grain',
        'discolour',
        'short_grain',

        'paddy_length',
        'bran_length',
        'milled_length',
        'impurity',

        'rejected_bags',
        'rejected_weight',
        'recommended_price',

        'analyst',
        'note',
        'user_id',
    ];


    public function procurement():BelongsTo{
        return $this->belongsTo(Procurement::class);
    }


    protected function analysisDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d-m-Y'),
            set: fn (string $value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }


}
