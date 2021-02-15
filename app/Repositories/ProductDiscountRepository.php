<?php

namespace App\Repositories;

use App\ProductDiscount;
use Illuminate\Support\Collection;

class ProductDiscountRepository
{
    /**
     * @param int $productId
     * @return Collection|ProductDiscount[]
     */
    public function findByProductId(int $productId): Collection
    {
        return ProductDiscount::where(ProductDiscount::productId, $productId)->get();
    }
}
