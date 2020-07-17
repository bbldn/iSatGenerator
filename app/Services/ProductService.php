<?php

namespace App\Services;

use App\Category;
use App\Product;
use App\ProductCategory;
use App\ProductDiscontinued;
use App\ProductDiscount;
use App\SeoUrl;
use Illuminate\Support\Collection;

class ProductService extends Service
{
    /** @var array $productsDiscontinued */
    protected $productsDiscontinued = [];

    /** @var array $seoUrls */
    protected $seoUrls = [];

    /**
     *
     */
    public function init(): void
    {
        $this->loadProductsDiscontinued();
        $this->loadSeoUrl();
    }

    /**
     * @return array
     */
    public function getData(): array
    {

        /** @var Collection|Category[] $mainCategories */
        $mainCategories = Category::where('parent_id', 0)
            ->where('status', true)
            ->with('categoryDescription')
            ->get();

        $result = [];
        foreach ($mainCategories as $category) {
            $categories = Category::where('parent_id', $category->category_id)->get(['category_id']);
            $categories[] = $category;

            $categoriesIds = $this->getColumn($categories, 'category_id');
            /* @noinspection PhpUndefinedMethodInspection */
            $subCategoriesIds = ProductCategory::whereIn('category_id', $categoriesIds)->get();

            $key = "category_id={$category->category_id}";
            if (true === key_exists($key, $this->seoUrls)) {
                $url = sprintf('/%s', $this->seoUrls[$key]);
            } else {
                $url = "/index.php?route=product/category&category_id={$category->category_id}";
            }

            $result[] = [
                'category_id' => $category->category_id,
                'name' => $category->categoryDescription->name,
                'url' => $url,
                'products' => $this->getProductsByProductsCategories($subCategoriesIds),
            ];
        }


        return $result;
    }

    /**
     *
     */
    protected function loadProductsDiscontinued(): void
    {
        if (count($this->productsDiscontinued) > 0) {
            return;
        }

        foreach (ProductDiscontinued::all() as $productDiscontinued) {
            /** @var ProductDiscontinued $productDiscontinued */
            $this->productsDiscontinued[$productDiscontinued->product_id] = null;
        }
    }

    /**
     *
     */
    protected function loadSeoUrl(): void
    {
        if (count($this->seoUrls) > 0) {
            return;
        }

        foreach (SeoUrl::all() as $seoUrl) {
            /** @var SeoUrl $seoUrl */
            $this->seoUrls[$seoUrl->query] = $seoUrl->keyword;
        }
    }

    /**
     * @param Collection|ProductCategory[] $productCategories
     * @return array
     */
    protected function getProductsByProductsCategories(Collection $productCategories): array
    {
        $mainCategories = [];
        $productIds = [];

        foreach ($productCategories as $item) {
            $productIds[] = $item->product_id;
            if (true === $item->main_category) {
                $mainCategories[$item->product_id] = $item->category_id;
            }
        }

        /* @noinspection PhpUndefinedMethodInspection */
        $products = Product::whereIn('product_id', $this->getColumn($productCategories, 'product_id'))
            ->orderBy('sort_order')
            ->with('productDescription')
            ->where('price', '>', 0.0)
            ->get();

        $result = [];
        /** @var Collection|Product[] $products */
        foreach ($products as $product) {
            if (true === key_exists($product->product_id, $this->productsDiscontinued)) {
                continue;
            }

            if (null === $product->productDescription) {
                continue;
            }

            $url = null;
            $key = "product_id={$product->product_id}";
            if (true === key_exists($key, $this->seoUrls)) {
                if (true === key_exists($product->product_id, $mainCategories)) {
                    $query = 'category_id=' . $mainCategories[$product->product_id];

                    if (true === key_exists($query, $this->seoUrls)) {
                        $categoryUrl = $this->seoUrls[$query];
                        $url = sprintf('/%s/%s', $categoryUrl, $this->seoUrls[$key]);
                    }
                }
            }

            if (null === $url) {
                $url = "/index.php?route=product/product&product_id={$product->product_id}";
            }

            $item = [
                'product_id' => $product->product_id,
                'price' => round($product->price, 0),
                'name' => $product->productDescription->name,
                'url' => $url,
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
     * @param Collection $array
     * @param string $column
     * @return array
     */
    protected function getColumn(Collection $array, string $column): array
    {
        $result = [];
        foreach ($array as $value) {
            $result[] = $value->$column;
        }

        return $result;
    }
}
