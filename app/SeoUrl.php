<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property integer seo_url_id
 * @property integer store_id
 * @property integer language_id
 * @property string query
 * @property string keyword
 * @method static SeoUrl|null find(integer $id)
 * @method static Collection all(array $columns = ['*'])
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static SeoUrl create(array $attributes)
 */
class SeoUrl extends Model
{
    /** @var string[] $fillable */
    protected $fillable = [
        'store_id', 'language_id',
        'query', 'keyword',
    ];

    /** @var bool $timestamps */
    public $timestamps = false;

    /** @var string $table */
    protected $table = 'oc_seo_url';

    /** @var string $primaryKey */
    protected $primaryKey = 'seo_url_id';
}
