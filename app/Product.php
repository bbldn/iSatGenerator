<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
 * @property ProductDiscount[] productDiscounts
 * @property ProductCategory[] productCategories
 * @method static Product|null find(integer $id)
 * @method static Collection all(array $columns = ['*'])
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Product create(array $attributes)
 */
class Product extends Model
{
    public const ean = 'ean';

    public const upc = 'upc';

    public const jan = 'jan';

    public const mpn = 'mpn';

    public const sku = 'sku';

    public const isbn = 'isbn';

    public const image = 'image';

    public const model = 'model';

    public const price = 'price';

    public const width = 'width';

    public const points = 'points';

    public const length = 'length';

    public const height = 'height';

    public const weight = 'weight';

    public const status = 'status';

    public const viewed = 'viewed';

    public const minimum = 'minimum';

    public const subtract = 'subtract';

    public const location = 'location';

    public const quantity = 'quantity';

    public const shipping = 'shipping';

    public const dateAdded = 'date_added';

    public const productId = 'product_id';

    public const sortOrder = 'sort_order';

    public const taxClassId = 'tax_class_id';

    public const dateModified = 'date_modified';

    public const dateAvailable = 'date_available';

    public const lengthClassId = 'length_class_id';

    public const weightClassId = 'weight_class_id';

    public const stockStatusId = 'stock_status_id';

    public const manufacturerId = 'manufacturer_id';

    /** @var string[] */
    protected $fillable = [
        self::ean,
        self::upc,
        self::jan,
        self::mpn,
        self::sku,
        self::isbn,
        self::image,
        self::model,
        self::price,
        self::width,
        self::points,
        self::length,
        self::height,
        self::weight,
        self::status,
        self::viewed,
        self::minimum,
        self::subtract,
        self::location,
        self::quantity,
        self::shipping,
        self::dateAdded,
        self::sortOrder,
        self::taxClassId,
        self::dateModified,
        self::dateAvailable,
        self::lengthClassId,
        self::weightClassId,
        self::stockStatusId,
        self::manufacturerId,
    ];

    /** @var string[] */
    protected $dates = [
        self::dateAdded,
        self::dateModified,
        self::dateAvailable,
    ];

    /** @var array */
    protected $casts = [
        self::status => 'bool',
        self::price => 'float',
        self::width => 'float',
        self::height => 'float',
        self::weight => 'float',
        self::length => 'float',
        self::shipping => 'bool',
        self::subtract => 'bool',
    ];

    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $table = 'oc_product';

    /** @var string */
    protected $primaryKey = self::productId;

    /**
     * @return HasMany
     */
    public function productDiscounts(): HasMany
    {
        return $this->hasMany(ProductDiscount::class, self::productId, self::productId);
    }

    /**
     * @return HasOne
     */
    public function productDescription(): HasOne
    {
        return $this->hasOne(ProductDescription::class, self::productId, self::productId);
    }

    /**
     * @return HasOne
     */
    public function productDiscontinued(): HasOne
    {
        return $this->hasOne(ProductDiscontinued::class, self::productId, self::productId);
    }

    /**
     * @return HasMany
     */
    public function productCategories(): HasMany
    {
        return $this->hasMany(ProductCategory::class, self::productId, self::productId);
    }
}
