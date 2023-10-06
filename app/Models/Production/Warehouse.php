<?php

namespace App\Models\Production;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warehouse extends Model
{
    use HasFactory;
    protected $table = 'production_warehouses';

    protected $fillable = [
        'count_id',
        'code',
        'production_id',
        'release_date',
        'weight',
        'released_by',
        'note',
        'user_id',
    ];



    public function production():BelongsTo{
        return $this->belongsTo(Production::class);
    }



    protected function releaseDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d-m-Y'),
            set: fn (string $value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }
}
