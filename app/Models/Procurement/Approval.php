<?php

namespace App\Models\Procurement;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'procurement_id',
        'approval_date',
        'approved_by',
        'approved_price',
        'status',
        'note',
        'user_id',
    ];


    public function procurement():BelongsTo
    {
        return $this->belongsTo(Procurement::class);
    }


    protected function approvalDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d-m-Y'),
            set: fn (string $value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

}
