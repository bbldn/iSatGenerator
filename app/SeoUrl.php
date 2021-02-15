<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property string|null query
 * @property string|null keyword
 * @property integer|null store_id
 * @property integer|null seo_url_id
 * @property integer|null language_id
 * @method static SeoUrl|null find(integer $id)
 * @method static Collection|SeoUrl[] all(array $columns = ['*'])
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static SeoUrl create(array $attributes)
 */
class SeoUrl extends Model
{
    public const query = 'query';

    public const keyword = 'keyword';

    public const storeId = 'store_id';

    public const seoUrlId = 'seo_url_id';

    public const languageId = 'language_id';

    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $table = 'oc_seo_url';

    /** @var string */
    protected $primaryKey = self::seoUrlId;

    /** @var string[] */
    protected $fillable = [
        self::query,
        self::keyword,
        self::storeId,
        self::languageId,
    ];
}
