<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property float|null price
 * @property Product|null product
 * @property integer|null quantity
 * @property integer|null priority
 * @property DateTime|null date_end
 * @property integer|null product_id
 * @property DateTime|null date_start
 * @property integer|null customer_group_id
 * @property integer|null product_discount_id
 * @property ProductCategory[] productCategories
 * @property ProductDescription|null productDescription
 * @property ProductDiscontinued|null productDiscontinued
 * @method static ProductDiscount|null find(integer $id)
 * @method static Collection all(array $columns = ['*'])
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static ProductDiscount create(array $attributes)
 */
class ProductDiscount extends Model
{
    public const price = 'price';

    public const dateEnd = 'date_end';

    public const priority = 'priority';

    public const quantity = 'quantity';

    public const productId = 'product_id';

    public const dateStart = 'date_start';

    public const customerGroupId = 'customer_group_id';

    public const productDiscountId = 'product_discount_id';

    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $table = 'oc_product_discount';

    /** @var string */
    protected $primaryKey = self::productDiscountId;

    /** @var string[] */
    protected $dates = [
        self::dateEnd,
        self::dateStart,
    ];

    /** @var array<string, string> */
    protected $casts = [
        self::price => 'float',
    ];

    /** @var string[] */
    protected $fillable = [
        self::price,
        self::dateEnd,
        self::quantity,
        self::priority,
        self::dateStart,
        self::productId,
        self::customerGroupId,
    ];

    /**
     * @return HasOne
     */
    public function product(): HasOne
    {
        return $this->hasOne(
            Product::class,
            Product::productId,
            self::productId
        );
    }

    /**
     * @return HasOne
     */
    public function productDescription(): HasOne
    {
        return $this->hasOne(
            ProductDescription::class,
            ProductDescription::productId,
            self::productId
        );
    }

    /**
     * @return HasOne
     */
    public function productDiscontinued(): HasOne
    {
        return $this->hasOne(
            ProductDiscontinued::class,
            ProductDiscontinued::productId,
            self::productId
        );
    }

    /**
     * @return HasMany
     */
    public function productCategories(): HasMany
    {
        return $this->hasMany(
            ProductCategory::class,
            ProductCategory::productId,
            self::productId
        );
    }
}
