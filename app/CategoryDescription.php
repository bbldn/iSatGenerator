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
    /** @var string[] $fillable */
    protected $fillable = [
        'language_id', 'name',
        'description', 'meta_title',
        'meta_description', 'meta_keyword'
    ];

    /** @var bool $timestamps */
    public $timestamps = false;

    /** @var string $table */
    protected $table = 'oc_category_description';

    /** @var string $primaryKey */
    protected $primaryKey = 'category_id';

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
