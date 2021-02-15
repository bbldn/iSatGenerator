<?php

namespace App\Repositories;

use App\ProductCategory;
use Illuminate\Support\Collection;

class ProductCategoryRepository
{
    /**
     * @param int[] $ids
     * @return Collection|ProductCategory[]
     */
    public function findByCategoryIds(array $ids): Collection
    {
        /* @noinspection PhpUndefinedMethodInspection */
        return ProductCategory::whereIn(ProductCategory::categoryId, $ids)->get();
    }
}
