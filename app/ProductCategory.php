<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property integer product_id
 * @property integer category_id
 * @property Product|null product
 * @property ProductDescription|null productDescription
 * @property ProductDiscontinued|null productDiscontinued
 * @property ProductDiscount[] productDiscounts
 * @method static Product|null find(integer $id)
 * @method static Collection all(array $columns = ['*'])
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Product create(array $attributes)
 */
class ProductCategory extends Model
{
    /** @var string[] $fillable */
    protected $fillable = [
        'category_id',
    ];

    /** @var bool $timestamps */
    public $timestamps = false;

    /** @var string $table */
    protected $table = 'oc_product_to_category';

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
     * @return HasMany
     */
    public function productDiscounts(): HasMany
    {
        return $this->hasMany(ProductDiscount::class, 'product_id', 'product_id');
    }

    /**
     * @return HasOne
     */
    public function productDescription(): HasOne
    {
        return $this->hasOne(ProductDescription::class, 'product_id', 'product_id');
    }

    /**
     * @return HasOne
     */
    public function productDiscontinued(): HasOne
    {
        return $this->hasOne(ProductDiscontinued::class, 'product_id', 'product_id');
    }
}
