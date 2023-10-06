<?php

namespace App\Models\Production;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Store extends Model
{
    use HasFactory;
    protected $table = 'production_stores';

    protected $fillable = [
        'production_id',
        'received_date',
        'product_id',
        'weight',
        'bags',
        'received_by',
        'user_id',
    ];


    public function production():BelongsTo{
        return $this->belongsTo(Production::class);
    }

    public function product():BelongsTo{
        return $this->belongsTo(Product::class);
    }

    protected function receivedDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d-m-Y'),
            set: fn (string $value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

}
