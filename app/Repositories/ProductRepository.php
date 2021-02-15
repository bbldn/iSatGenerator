<?php

namespace App\Repositories;

use App\Product;
use Illuminate\Support\Collection;

class ProductRepository
{
    /**
     * @param int[] $ids
     * @param float $price
     * @return Collection|Product[]
     */
    public function findByIdsAndPrice(array $ids, float $price): Collection
    {
        /* @noinspection PhpUndefinedMethodInspection */
        return Product::whereIn(Product::productId, $ids)
            ->orderBy(Product::sortOrder)
            ->with(Product::productDescription)
            ->where(Product::price, '>', $price)
            ->get();
    }
}
