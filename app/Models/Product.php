<?php

namespace App\Models;

use App\Models\Procurement\Procurement;
use App\Models\Production\Output;
use App\Models\Production\Production;
use App\Models\Sales\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';


    public function productions():HasMany{
        return $this->hasMany(Production::class);
    }
    public function output():HasMany{
        return $this->hasMany(Output::class);
    }

    public function invoice_items():HasMany{
        return $this->hasMany(InvoiceItem::class);
    }





//    public function stores():HasMany{
//        return $this->hasMany(Production::class);
//    }




}
