<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property integer product_id
 * @property integer language_id
 * @property string name
 * @property string description
 * @property string tag
 * @property string meta_title
 * @property string meta_description
 * @property string meta_keyword
 * @property Product|null product
 * @property ProductDiscount[] productDiscounts
 * @property ProductDiscontinued|null productDiscontinued
 * @property ProductCategory[] productCategories
 * @method static Product|null find(integer $id)
 * @method static Collection all(array $columns = ['*'])
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Product create(array $attributes)
 */
class ProductDescription extends Model
{
    /** @var string[] $fillable */
    protected $fillable = [
        'language_id', 'name',
        'description', 'tag',
        'meta_title', 'meta_description',
        'meta_keyword'
    ];

    /** @var bool $timestamps */
    public $timestamps = false;

    /** @var string $table */
    protected $table = 'oc_product_description';

    /** @var string $primaryKey */
    protected $primaryKey = 'product_id';

    /**
     * @return HasOne
     */
    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'product_id', 'product_id');
    }

    /**
     * @return HasOne
     */
    public function productDescription(): HasOne
    {
        return $this->hasOne(ProductDescription::class, 'product_id', 'product_id');
    }

    /**
     * @return HasMany
     */
    public function productDiscounts(): HasMany
    {
        return $this->hasMany(ProductDiscount::class, 'product_id', 'product_id');
    }

    /**
     * @return HasMany
     */
    public function productCategories(): HasMany
    {
        return $this->hasMany(ProductCategory::class, 'product_id', 'product_id');
    }
}
