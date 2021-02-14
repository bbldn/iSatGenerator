<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property string|null code
 * @property float|null value
 * @property bool|null status
 * @property string|null title
 * @property string|null symbol_left
 * @property string|null symbol_right
 * @property integer|null currency_id
 * @property integer|null decimal_place
 * @property DateTime|null date_modified
 * @method static Currency|null find(integer $id)
 * @method static Collection|Currency[] all(array $columns = ['*'])
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Currency create(array $attributes)
 */
class Currency extends Model
{
    public const code = 'code';

    public const title = 'title';

    public const value = 'value';

    public const status = 'status';

    public const symbolLeft = 'symbol_left';

    public const currencyId = 'currency_id';

    public const symbolRight = 'symbol_right';

    public const dateModified = 'date_modified';

    public const decimalPlace = 'decimal_place';

    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $table = 'oc_currency';

    /** @var string */
    protected $primaryKey = self::currencyId;

    /** @var string[] */
    protected $dates = [
        self::dateModified,
    ];

    /** @var array<string, string> */
    protected $casts = [
        self::value => 'float',
        self::status => 'bool',
        self::decimalPlace => 'int',
    ];

    /** @var string[] */
    protected $fillable = [
        self::code,
        self::value,
        self::title,
        self::status,
        self::symbolLeft,
        self::symbolRight,
        self::decimalPlace,
        self::dateModified,
    ];
}
