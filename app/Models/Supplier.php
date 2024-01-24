<?php

namespace App\Models;

use App\Models\Procurement\Procurement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'count_id',
        'code',
        'name',
        'phone',
        'email',
        'bank_name',
        'bank_account',
        'advance',
        'note',
        'user_id',
    ];

    public function procurements():HasMany{
        return $this->hasMany(Procurement::class);
    }
}
