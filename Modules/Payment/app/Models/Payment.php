<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Payment\Database\Factories\PaymentFactory;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'paymentable_id',
        'paymentable_type',
        'date',
        'type',
        'received_amount',
        'amount',
        'note',
    ];

    public static  function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->user_id = auth()->id();
        });
    }

    public function amount(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value / 1000,
            set: fn ($value) => $value * 1000
        );
    }

    public function receivedAmount(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value / 1000,
            set: fn ($value) => $value * 1000
        );
    }

    public function detailable()
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        return PaymentFactory::new();
    }
}
