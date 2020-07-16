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
 * @property integer product_id
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
    /** @var bool $timestamps */
    public $timestamps = false;

    /** @var string $table */
    protected $table = 'oc_category';

    /** @var string $primaryKey */
    protected $primaryKey = 'category_id';

    /** @var string[] $fillable */
    protected $fillable = [
        'image', 'parent_id', 'top',
        'column', 'sort_order', 'status',
        'page_group_links', 'date_added',
        'date_modified',
    ];

    /** @var string[] $dates */
    protected $dates = [
        'date_added',
        'date_modified',
    ];

    /** @var array $casts */
    protected $casts = [
        'top' => 'bool',
        'status' => 'bool',
    ];

    /**
     * @return HasOne
     */
    public function parent(): HasOne
    {
        return $this->hasOne(Category::class, 'category_id', 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function productsCategories(): HasMany
    {
        return $this->hasMany(ProductCategory::class, 'category_id', 'category_id');
    }

    /**
     * @return HasManyThrough
     */
    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(
            Product::class,
            ProductCategory::class,
            'category_id',
            'product_id',
            'product_id',
            'category_id'
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
            'category_id',
            'product_id',
            'product_id',
            'category_id'
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
            'category_id',
            'product_id',
            'product_id',
            'category_id'
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
            'category_id',
            'product_id',
            'product_id',
            'category_id'
        );
    }
}
