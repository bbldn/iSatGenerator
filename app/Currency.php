<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property integer currency_id
 * @property string title
 * @property string code
 * @property string symbol_left
 * @property string symbol_right
 * @property integer decimal_place
 * @property float value
 * @property bool status
 * @property DateTime date_modified
 * @method static Currency|null find(integer $id)
 * @method static Collection|Currency[] all(array $columns = ['*'])
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Currency create(array $attributes)
 */
class Currency extends Model
{
    /** @var bool $timestamps */
    public $timestamps = false;

    /** @var string $table */
    protected $table = 'oc_currency';

    /** @var string $primaryKey */
    protected $primaryKey = 'currency_id';

    /** @var string[] $fillable */
    protected $fillable = [
        'title', 'code', 'symbol_left',
        'symbol_right', 'decimal_place',
        'value', 'status', 'date_modified',
    ];

    /** @var string[] $dates */
    protected $dates = [
        'date_modified',
    ];

    /** @var array $casts */
    protected $casts = [
        'value' => 'float',
        'status' => 'bool',
    ];
}
