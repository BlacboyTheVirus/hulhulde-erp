<?php

namespace App\Models\Parts;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Usages extends Model
{
    use HasFactory;

    protected $fillable = [
        'parts_id',
        'date',
        'quantity',
        'collected_by',
        'note',
        'user_id',
    ];

    public function usages():BelongsTo{
        return $this->belongsTo(Parts::class);
    }


    protected function collectedDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d-m-Y'),
            set: fn (string $value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }
}
