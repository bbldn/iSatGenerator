<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property integer product_id
 * @property Product|null product
 * @property string|null redirect_url
 * @property ProductCategory[] productCategories
 * @property ProductDiscount|null productDiscount
 * @property ProductDescription|null productDescription
 * @method static Product|null find(integer $id)
 * @method static Product create(array $attributes)
 * @method static Collection all(array $columns = ['*'])
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 */
class ProductDiscontinued extends Model
{
    public const productId = 'product_id';

    public const redirectUrl = 'redirect_url';

    /** @var string[] */
    protected $fillable = [
        self::redirectUrl,
    ];

    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $primaryKey = self::productId;

    /** @var string */
    protected $table = 'oc_product_discontinued';

    /**
     * @return HasMany
     */
    public function productDiscount(): HasMany
    {
        return $this->hasMany(
            ProductDiscount::class,
            ProductDiscount::productId,
            self::productId
        );
    }

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
