<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;

/**
 * @property integer category_id
 * @property integer language_id
 * @property string name
 * @property string description
 * @property string meta_title
 * @property string meta_description
 * @property string meta_keyword
 * @property Product[] products
 * @property ProductDescription[] productsDescriptions
 * @property ProductDiscontinued[] productsDiscontinued
 * @property ProductDiscount[] productsDiscounts
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

    /** @var string[] */
    protected $fillable = [
        self::name,
        self::metaTitle,
        self::languageId,
        self::description,
        self::metaKeyword,
        self::metaDescription,
    ];

    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $table = 'oc_category_description';

    /** @var string */
    protected $primaryKey = self::categoryId;

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
