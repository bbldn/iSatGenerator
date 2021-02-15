<?php

namespace App\Repositories;

use App\ProductCategory;
use Illuminate\Database\Eloquent\Collection;

class ProductCategoryRepository
{
    /**
     * @param int[] $ids
     * @return Collection|ProductCategory[]
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     */
    public function findByCategoryIds(array $ids): Collection
    {
        /* @noinspection PhpUndefinedMethodInspection */
        return ProductCategory::whereIn(ProductCategory::categoryId, $ids)->get();
    }
}
