<?php

namespace App\Services;

use App\Category;
use App\Product;
use App\ProductCategory;
use App\ProductDiscontinued;
use App\ProductDiscount;
use Illuminate\Support\Collection;

class ProductService extends Service
{
    /** @var array $productsDiscontinued */
    protected $productsDiscontinued = [];

    /**
     * @return array
     */
    public function getData(): array
    {
        $this->loadProductsDiscontinued();
        /** @var Collection|Category[] $mainCategories */
        $mainCategories = Category::where('parent_id', 0)->with('categoryDescription')->get();
        $result = [];

        foreach ($mainCategories as $category) {
            $categories = Category::where('parent_id', $category->category_id)->get(['category_id']);
            $categories[] = $category;
            $subCategoriesIds = $this->getSubCategoriesByIds($this->getColumn($categories, 'category_id'));

            $result[] = [
                'category_id' => $category->category_id,
                'name' => $category->categoryDescription->name,
                'url' => "/index.php?route=category_id={$category->category_id}",
                'products' => $this->getProductsByIds($subCategoriesIds),
            ];
        }


        return $result;
    }

    /**
     *
     */
    protected function loadProductsDiscontinued(): void
    {
        if (0 === count($this->productsDiscontinued)) {
            return;
        }

        foreach (ProductDiscontinued::all() as $value) {
            /** @var ProductDiscontinued $value */
            $this->productsDiscontinued[$value->product_id] = null;
        }
    }

    /**
     * @param array $productCategories
     * @return array
     */
    protected function getSubCategoriesByIds(array $productCategories): array
    {
        /* @noinspection PhpUndefinedMethodInspection */
        $productCategories = ProductCategory::whereIn('category_id', $productCategories)->get(['product_id']);

        return $this->getColumn($productCategories, 'product_id');
    }

    /**
     * @param array $ids
     * @return array
     */
    protected function getProductsByIds(array $ids): array
    {
        /** @var Collection|Product[] $products */
        /* @noinspection PhpUndefinedMethodInspection */
        $products = Product::whereIn('product_id', $ids)
            ->orderBy('sort_order')
            ->with('productDescription')
            ->where('price', '>', 0.0)
            ->get();

        $result = [];
        foreach ($products as $product) {
            if (null === $product->productDescription || key_exists($product->product_id, $this->productsDiscontinued)) {
                continue;
            }

            $item = [
                'product_id' => $product->product_id,
                'price' => round($product->price, 0),
                'name' => $product->productDescription->name,
                'url' => "/index.php?route=product_id={$product->product_id}",
            ];

            $result[] = array_merge($item, $this->getPriceByProductId($product->product_id));
        }

        return $result;
    }

    /**
     * @param int $id
     * @return array
     */
    protected function getPriceByProductId(int $id): array
    {
        $result = [
            'retail' => 0.0, 'dealer' => 0.0,
            'wholesale' => 0.0, 'partner' => 0.0,
        ];

        $pricesById = [
            1 => 'retail', 2 => 'dealer',
            3 => 'wholesale', 4 => 'partner',
        ];

        /** @var ProductDiscount[]|Collection $discounts */
        $discounts = ProductDiscount::where('product_id', $id)->get();
        foreach ($discounts as $discount) {
            if (false === key_exists($discount->customer_group_id, $pricesById)) {
                continue;
            }

            $result[$pricesById[$discount->customer_group_id]] = round($discount->price, 0);
        }

        return $result;
    }

    /**
     * @param object $array $array
     * @param string $column
     * @return array
     */
    protected function getColumn(object $array, string $column): array
    {
        $result = [];
        foreach ($array as $value) {
            $result[] = $value->$column;
        }

        return $result;
    }
}
