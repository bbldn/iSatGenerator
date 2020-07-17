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
     * @return array
     */
    public function getData(): array
    {
        $this->loadProductsDiscontinued();
        $this->loadSeoUrl();
        /** @var Collection|Category[] $mainCategories */
        $mainCategories = Category::where('parent_id', 0)->with('categoryDescription')->get();
        $result = [];

        foreach ($mainCategories as $category) {
            $categories = Category::where('parent_id', $category->category_id)->get(['category_id']);
            $categories[] = $category;
            $subCategoriesIds = $this->getSubCategoriesByIds($this->getColumn($categories, 'category_id'));

            $key = "category_id={$category->category_id}";
            if (true === key_exists($key, $this->seoUrls)) {
                $url = $this->seoUrls[$key];
            } else {
                $url = "/index.php?route=product/category&category_id={$category->category_id}";
            }

            $result[] = [
                'category_id' => $category->category_id,
                'name' => $category->categoryDescription->name,
                'url' => $url,
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
            if (null === $product->productDescription) {
                continue;
            }

            if (true === key_exists($product->product_id, $this->productsDiscontinued)) {
                continue;
            }

            $key = "product_id={$product->product_id}";
            if (true === key_exists($key, $this->seoUrls)) {
                $url = $this->seoUrls[$key];
            } else {
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
