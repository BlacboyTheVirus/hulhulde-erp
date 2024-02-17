<?php

namespace App\Models\Production;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Output extends Model
{
    use HasFactory;
    protected $table = 'production_outputs';

    protected $fillable = [
        'production_id',
        'production_date',
        'shift',
        'product_id',
        'weight',
        'bags',
        'user_id',
    ];

    public function production():BelongsTo{
        return $this->belongsTo(Production::class);
    }

    public function product():BelongsTo{
        return $this->belongsTo(Product::class);
    }



    protected function productionDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d-m-Y'),
            set: fn (string $value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }





}
