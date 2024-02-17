<?php

namespace App\Models\Parts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parts extends Model
{
    use HasFactory;

    protected $fillable = [
        'count_id',
        'code',
        'name',
        'description',
        'unit',
        'quantity',
        'restock_level',
        'user_id',
    ];


    public function stockings():HasMany{
        return $this->hasMany(Stockings::class);
    }

    public function usages():HasMany{
        return $this->hasMany(Usages::class);
    }



}
