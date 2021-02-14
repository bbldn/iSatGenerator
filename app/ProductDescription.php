<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property string|null tag
 * @property string|null name
 * @property Product|null product
 * @property string|null meta_title
 * @property string|null description
 * @property integer|null product_id
 * @property integer|null language_id
 * @property string|null meta_keyword
 * @property string|null meta_description
 * @property ProductDiscount[] productDiscounts
 * @property ProductCategory[] productCategories
 * @property ProductDiscontinued|null productDiscontinued
 * @method static Product|null find(integer $id)
 * @method static Product create(array $attributes)
 * @method static Collection all(array $columns = ['*'])
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 */
class ProductDescription extends Model
{
    public const tag = 'tag';

    public const name = 'name';

    public const productId = 'product_id';

    public const metaTitle = 'meta_title';

    public const languageId = 'language_id';

    public const description = 'description';

    public const metaKeyword = 'meta_keyword';

    public const metaDescription = 'meta_description';

    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $primaryKey = self::productId;

    /** @var string */
    protected $table = 'oc_product_description';

    /** @var string[] */
    protected $fillable = [
        self::tag,
        self::name,
        self::metaTitle,
        self::languageId,
        self::description,
        self::metaKeyword,
        self::metaDescription,
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
     * @return HasMany
     */
    public function productCategories(): HasMany
    {
        return $this->hasMany(
            ProductCategory::class,
            Product::productId,
            self::productId
        );
    }
}
