<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property bool main_category
 * @property integer product_id
 * @property integer category_id
 * @property Product|null product
 * @property Category|null category
 * @property ProductDiscount[] productDiscounts
 * @property ProductDescription|null productDescription
 * @property ProductDiscontinued|null productDiscontinued
 * @method static Product|null find(integer $id)
 * @method static Product create(array $attributes)
 * @method static Collection all(array $columns = ['*'])
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 */
class ProductCategory extends Model
{
    public const productId = 'product_id';

    public const categoryId = 'category_id';

    public const mainCategory = 'main_category';

    /** @var string[] */
    protected $fillable = [
        self::categoryId,
        self::mainCategory,
    ];

    /** @var array */
    protected $casts = [
        self::mainCategory => 'bool',
    ];

    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $primaryKey = self::productId;

    /** @var string */
    protected $table = 'oc_product_to_category';

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
     * @return HasMany
     */
    public function productDiscounts(): HasMany
    {
        return $this->hasMany(
            ProductDiscount::class,
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
            Product::productId,
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
            Product::productId,
            self::productId
        );
    }

    /**
     * @return HasOne
     */
    public function category(): HasOne
    {
        return $this->hasOne(
            Category::class,
            Category::categoryId,
            self::categoryId
        );
    }
}
