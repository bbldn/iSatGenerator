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
 * @property integer category_id
 * @property string|null image
 * @property integer parent_id
 * @property bool top
 * @property integer column
 * @property integer sort_order
 * @property bool status
 * @property string page_group_links
 * @property DateTime date_added
 * @property DateTime date_modified
 * @property Category|null parent
 * @property CategoryDescription|null categoryDescription
 * @property ProductCategory[] productsCategories
 * @property Product[] products
 * @property ProductDescription[] productsDescriptions
 * @property ProductDiscontinued[] productsDiscontinued
 * @property ProductDiscount[] productsDiscounts
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

    /**
     * @return HasOne
     */
    public function parent(): HasOne
    {
        return $this->hasOne(Category::class, self::categoryId, self::parentId);
    }

    /**
     * @return HasOne
     */
    public function categoryDescription(): HasOne
    {
        return $this->hasOne(CategoryDescription::class, self::categoryId, self::categoryId);
    }

    /**
     * @return HasMany
     */
    public function productsCategories(): HasMany
    {
        return $this->hasMany(ProductCategory::class, self::categoryId, self::categoryId);
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
