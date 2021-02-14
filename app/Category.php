<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property bool|null top
 * @property bool|null status
 * @property string|null image
 * @property integer|null column
 * @property integer|null parent_id
 * @property integer|null sort_order
 * @property integer|null category_id
 * @property DateTime|null date_added
 * @property DateTime|null date_modified
 * @property string|null page_group_links
 * @property Product[] products
 * @property Category|null parent
 * @property ProductDiscount[] productsDiscounts
 * @property ProductCategory[] productsCategories
 * @property ProductDescription[] productsDescriptions
 * @property ProductDiscontinued[] productsDiscontinued
 * @property CategoryDescription|null categoryDescription
 * @method static Category|null find(integer $id)
 * @method static Collection|Category[] all(array $columns = ['*'])
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Category create(array $attributes)
 */
class Category extends Model
{
    public const top = 'top';

    public const image = 'image';

    public const column = 'column';

    public const status = 'status';

    public const parentId = 'parent_id';

    public const sortOrder = 'sort_order';

    public const dateAdded = 'date_added';

    public const categoryId = 'category_id';

    public const dateModified = 'date_modified';

    public const pageGroupLinks = 'page_group_links';

    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $table = 'oc_category';

    /** @var string */
    protected $primaryKey = self::categoryId;

    /** @var string[] */
    protected $dates = [
        self::dateAdded,
        self::dateModified,
    ];

    /** @var array */
    protected $casts = [
        self::top => 'bool',
        self::status => 'bool',
    ];

    /** @var string[] */
    protected $fillable = [
        self::top,
        self::image,
        self::column,
        self::status,
        self::parentId,
        self::sortOrder,
        self::dateAdded,
        self::dateModified,
        self::pageGroupLinks,
    ];

    /**
     * @return HasOne
     */
    public function parent(): HasOne
    {
        return $this->hasOne(
            Category::class,
            Category::categoryId,
            self::parentId
        );
    }

    /**
     * @return HasOne
     */
    public function categoryDescription(): HasOne
    {
        return $this->hasOne(
            CategoryDescription::class,
            CategoryDescription::categoryId,
            self::categoryId
        );
    }

    /**
     * @return HasMany
     */
    public function productsCategories(): HasMany
    {
        return $this->hasMany(
            ProductCategory::class,
            ProductCategory::categoryId,
            self::categoryId
        );
    }

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
