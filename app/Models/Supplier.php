<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'count_id',
        'code',
        'supplier_id',
        'item_id',
        'procurement_date',
        'expected_weight',
        'expected_bags',
        'location',
        'note',
        'status',
        'user_id',
    ];

    public function procurements():HasMany{
        return $this->hasMany(Procurement::class);
    }
}
