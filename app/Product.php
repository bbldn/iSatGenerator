<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * @property integer product_id
 * @property string model
 * @property string sku
 * @property string upc
 * @property string ean
 * @property string jan
 * @property string isbn
 * @property string mpn
 * @property string location
 * @property integer quantity
 * @property integer stock_status_id
 * @property string image
 * @property integer manufacturer_id
 * @property bool shipping
 * @property float price
 * @property integer points
 * @property integer tax_class_id
 * @property DateTime date_available
 * @property float weight
 * @property integer weight_class_id
 * @property float length
 * @property float width
 * @property float height
 * @property integer length_class_id
 * @property bool subtract
 * @property integer minimum
 * @property integer sort_order
 * @property bool status
 * @property integer viewed
 * @property DateTime date_added
 * @property DateTime date_modified
 * @property ProductDescription|null productDescription
 * @property ProductDiscontinued|null productDiscontinued
 * @property ProductDiscount|null productDiscount
 * @method static Product|null find(integer $id)
 * @method static Collection all(array $columns = ['*'])
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Product create(array $attributes)
 */
class Product extends Model
{
    /** @var string[] $fillable */
    protected $fillable = [
        'model', 'sku', 'upc',
        'ean', 'jan', 'isbn',
        'mpn', 'location', 'quantity',
        'stock_status_id', 'image',
        'manufacturer_id', 'shipping',
        'price', 'points', 'tax_class_id',
        'date_available', 'weight',
        'weight_class_id', 'length',
        'width', 'height', 'length_class_id',
        'subtract', 'minimum', 'sort_order',
        'status', 'viewed', 'date_added',
        'date_modified',
    ];

    /** @var string[] $dates */
    protected $dates = [
        'date_available',
        'date_added',
        'date_modified',
    ];

    /** @var array $casts */
    protected $casts = [
        'shipping' => 'bool',
        'price' => 'float',
        'weight' => 'float',
        'length' => 'float',
        'width' => 'float',
        'height' => 'float',
        'subtract' => 'bool',
        'status' => 'bool',
    ];

    /** @var bool $timestamps */
    public $timestamps = false;

    /** @var string $table */
    protected $table = 'oc_product';

    /** @var string $primaryKey */
    protected $primaryKey = 'product_id';

    /**
     * @return HasMany
     */
    public function productDiscount(): HasMany
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
