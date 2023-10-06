<?php

namespace App\Models;

use App\Models\Procurement\Procurement;
use App\Models\Production\Production;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Input extends Model
{
    use HasFactory;

    public function procurements():HasMany{
        return $this->hasMany(Procurement::class);
    }

    public function productions():HasMany{
        return $this->hasMany(Production::class);
    }

}
