<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property integer product_discount_id
 * @property integer product_id
 * @property integer customer_group_id
 * @property integer quantity
 * @property integer priority
 * @property float price
 * @property DateTime date_start
 * @property DateTime date_end
 * @property Product|null product
 * @property ProductDescription|null productDescription
 * @property ProductDiscontinued|null productDiscontinued
 * @method static ProductDiscount|null find(integer $id)
 * @method static Collection all(array $columns = ['*'])
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static ProductDiscount create(array $attributes)
 */
class ProductDiscount extends Model
{
    /** @var string[] $fillable */
    protected $fillable = [
        'product_id', 'customer_group_id',
        'quantity', 'priority', 'price',
        'date_start', 'date_end'
    ];

    /** @var string[] $dates */
    protected $dates = [
        'date_start',
        'date_end',
    ];

    /** @var array $casts */
    protected $casts = [
        'price' => 'float',
    ];

    /** @var bool $timestamps */
    public $timestamps = false;

    /** @var string $table */
    protected $table = 'oc_product_discount';

    /** @var string $primaryKey */
    protected $primaryKey = 'product_discount_id';

    /**
     * @return HasOne
     */
    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'product_id', 'product_id');
    }

    /**
     * @return HasOne
     */
    public function productDescription(): HasOne
    {
        return $this->hasOne(ProductDescription::class, 'product_id', 'product_id');
    }

    /**
     * @return HasOne
     */
    public function productDiscontinued(): HasOne
    {
        return $this->hasOne(ProductDiscontinued::class, 'product_id', 'product_id');
    }
}
