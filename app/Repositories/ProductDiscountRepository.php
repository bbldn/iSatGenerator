<?php

namespace App\Repositories;

use App\ProductDiscount;
use Illuminate\Database\Eloquent\Collection;

class ProductDiscountRepository
{
    /**
     * @param int $productId
     * @return Collection|ProductDiscount[]
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     */
    public function findByProductId(int $productId): Collection
    {
        return ProductDiscount::where(ProductDiscount::productId, $productId)->get();
    }
}
