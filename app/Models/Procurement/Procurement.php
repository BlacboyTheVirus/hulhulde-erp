<?php

namespace App\Models\Procurement;



use App\Models\Account;
use App\Models\Input;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Procurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'count_id',
        'code',
        'supplier_id',
        'input_id',
        'procurement_date',
        'expected_weight',
        'expected_bags',
        'location',
        'note',
        'status',
        'next',
        'user_id',
    ];

    public function supplier():BelongsTo{
        return $this->belongsTo(Supplier::class);
    }

    public function input():BelongsTo{
        return $this->belongsTo(Input::class);
    }


    public function security(): HasOne
    {
        return $this->hasOne(Security::class);
    }

    public function weighbridge(): HasOne
    {
        return $this->hasOne(Weighbridge::class);
    }

    public function quality(): HasOne
    {
        return $this->hasOne(Quality::class);
    }

    public function warehouse(): HasOne
    {
        return $this->hasOne(Warehouse::class);
    }

    public function account(): HasOne
    {
        return $this->hasOne(Account::class);
    }

    public function approval(): HasOne
    {
        return $this->hasOne(Approval::class);
    }


    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }



    protected function procurementDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d-m-Y'),
            set: fn (string $value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

}
