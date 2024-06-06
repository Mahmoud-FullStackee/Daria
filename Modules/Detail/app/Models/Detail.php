<?php

namespace Modules\Detail\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Detail\Database\Factories\DetailFactory;
use Modules\Item\Models\Item;
use Modules\Patch\Models\Patch;
use Modules\Stock\Models\Stock;
use Modules\Unit\Models\Unit;
use Modules\Variant\Models\Variant;

class Detail extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'detailable_id',
        'detailable_type',
        'amount',
        'tax',
        'tax_type',
        'discount',
        'discount_type',
        'unit_id',
        'item_id',
        'variant_id',
        'warehouse_id',
        'patch_id',
        'total',
        'quantity',
        'production_date',
        'expired_date',
        'product_type'
    ];

    
    protected $casts = [
        'product_type'      => \App\Enums\ProductTypesEnum::class,
        'tax_type'          => 'integer',
        'total'              => 'double',
        'quantity'              => 'double',
        'amount'              => 'double',
        'tax'              => 'double',

        'patch_id' => 'integer',
        'unit_id' => 'integer',
        'item_id' => 'integer',
        'variant_id' => 'integer',
        'warehouse_id' => 'integer',
    ];


    public function detailable()
    {
        return $this->morphTo();
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id')->withTrashed();
    }

    public function stock()
    {
        return $this->hasMany(Stock::class, 'item_id', 'item_id')->withTrashed();
    }

    public function item()
    {
        return $this->belongsTo(Item::class)->withTrashed();
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id')->withTrashed();
    }

    public function patch()
    {
        return $this->belongsTo(Patch::class)->withTrashed();
    }

    protected static function newFactory()
    {
        return DetailFactory::new();
    }
}
