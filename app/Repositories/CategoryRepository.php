<?php

namespace App\Repositories;

use App\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
    /**
     * @return Collection|Category[]
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     */
    public function findParentCategoriesWithCategoryDescription(): Collection
    {
        return Category::where(Category::parentId, 0)
            ->where(Category::status, true)
            ->with(Category::categoryDescription)
            ->get();
    }

    /**
     * @param int $categoryId
     * @return Collection|Category[]
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     */
    public function findByParentId(int $categoryId): Collection
    {
        return Category::where(Category::parentId, $categoryId)->get();
    }
}
