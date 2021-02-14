<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;

/**
 * @property string|null name
 * @property Product[] products
 * @property string|null meta_title
 * @property string|null description
 * @property string|null meta_keyword
 * @property integer|null category_id
 * @property integer|null language_id
 * @property string|null meta_description
 * @property ProductDiscount[] productsDiscounts
 * @property ProductDescription[] productsDescriptions
 * @property ProductDiscontinued[] productsDiscontinued
 * @method static CategoryDescription|null find(integer $id)
 * @method static Collection|CategoryDescription[] all(array $columns = ['*'])
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static CategoryDescription create(array $attributes)
 */
class CategoryDescription extends Model
{
    public const name = 'name';

    public const metaTitle = 'meta_title';

    public const languageId = 'language_id';

    public const categoryId = 'category_id';

    public const description = 'description';

    public const metaKeyword = 'meta_keyword';

    public const metaDescription = 'meta_description';

    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $primaryKey = self::categoryId;

    /** @var string */
    protected $table = 'oc_category_description';

    /** @var string[] */
    protected $fillable = [
        self::name,
        self::metaTitle,
        self::languageId,
        self::description,
        self::metaKeyword,
        self::metaDescription,
    ];

    /**
     * @return HasManyThrough
     */
    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(
            Product::class,
            ProductCategory::class,
            self::categoryId,
            Product::productId,
            Product::productId,
            self::categoryId
        );
    }

    /**
     * @return HasManyThrough
     */
    public function productsDescriptions(): HasManyThrough
    {
        return $this->hasManyThrough(
            ProductDescription::class,
            ProductCategory::class,
            self::categoryId,
            Product::productId,
            Product::productId,
            self::categoryId
        );
    }

    /**
     * @return HasManyThrough
     */
    public function productsDiscontinued(): HasManyThrough
    {
        return $this->hasManyThrough(
            ProductDiscontinued::class,
            ProductCategory::class,
            self::categoryId,
            Product::productId,
            Product::productId,
            self::categoryId
        );
    }

    /**
     * @return HasManyThrough
     */
    public function productsDiscounts(): HasManyThrough
    {
        return $this->hasManyThrough(
            ProductDiscount::class,
            ProductCategory::class,
            self::categoryId,
            Product::productId,
            Product::productId,
            self::categoryId
        );
    }
}
