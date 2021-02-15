<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property string|null sku
 * @property string|null upc
 * @property string|null ean
 * @property string|null jan
 * @property string|null mpn
 * @property string|null isbn
 * @property float|null price
 * @property bool|null status
 * @property float|null width
 * @property string|null model
 * @property float|null height
 * @property float|null weight
 * @property float|null length
 * @property string|null image
 * @property bool|null shipping
 * @property bool|null subtract
 * @property integer|null viewed
 * @property integer|null points
 * @property integer|null minimum
 * @property string|null location
 * @property integer|null quantity
 * @property integer|null product_id
 * @property integer|null sort_order
 * @property DateTime|null date_added
 * @property integer|null tax_class_id
 * @property DateTime|null date_modified
 * @property DateTime|null date_available
 * @property integer|null stock_status_id
 * @property integer|null manufacturer_id
 * @property integer|null weight_class_id
 * @property integer|null length_class_id
 * @property ProductDiscount[] productDiscounts
 * @property ProductCategory[] productCategories
 * @property ProductDescription|null productDescription
 * @property ProductDiscontinued|null productDiscontinued
 * @method static Product|null find(integer $id)
 * @method static Collection all(array $columns = ['*'])
 * @method static Builder where($column, $operator = null, $value = null, $bool = 'and')
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

    public const productDescription = 'productDescription';

    /** @var string[] */
    protected $dates = [
        self::dateAdded,
        self::dateModified,
        self::dateAvailable,
    ];

    /** @var array<string, string> */
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
        return $this->hasMany(
            ProductDiscount::class,
            ProductDiscount::productId,
            self::productId
        );
    }

    /**
     * @return HasOne
     */
    public function productDescription(): HasOne
    {
        return $this->hasOne(
            ProductDescription::class,
            ProductDescription::productId,
            self::productId
        );
    }

    /**
     * @return HasOne
     */
    public function productDiscontinued(): HasOne
    {
        return $this->hasOne(
            ProductDiscontinued::class,
            ProductDiscontinued::productId,
            self::productId
        );
    }

    /**
     * @return HasMany
     */
    public function productCategories(): HasMany
    {
        return $this->hasMany(
            ProductCategory::class,
            ProductCategory::productId,
            self::productId
        );
    }
}
