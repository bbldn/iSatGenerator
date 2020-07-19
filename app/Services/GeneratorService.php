<?php

namespace App\Services;

use App\Category;
use App\Currency;
use App\Helper\Store;
use App\Product;
use App\ProductCategory;
use App\ProductDiscontinued;
use App\ProductDiscount;
use App\SeoUrl;
use Illuminate\Support\Collection;

class GeneratorService extends Service
{
    /** @var array $productsDiscontinued */
    protected $productsDiscontinued = [];

    /** @var array $seoUrls */
    protected $seoUrls = [];

    /** @var string $siteUrl */
    protected $siteUrl;

    /**
     * ProductService constructor.
     * @param string $siteUrl
     */
    public function __construct(string $siteUrl)
    {
        $this->siteUrl = $siteUrl;
    }

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

        $resultCategories = [];
        foreach ($mainCategories as $category) {
            if (null === $category->categoryDescription) {
                continue;
            }

            $categories = Category::where('parent_id', $category->category_id)
                ->get(['category_id'])
                ->push($category);

            $categoriesIds = $this->getColumn($categories, 'category_id');
            /* @noinspection PhpUndefinedMethodInspection */
            $subCategoriesIds = ProductCategory::whereIn('category_id', $categoriesIds)->get();

            $key = "category_id={$category->category_id}";
            if (true === key_exists($key, $this->seoUrls)) {
                $url = sprintf("{$this->siteUrl}/%s", $this->seoUrls[$key]);
            } else {
                $url = "{$this->siteUrl}/index.php?route=product/category&category_id={$category->category_id}";
            }

            $resultCategories[] = [
                'category_id' => $category->category_id,
                'name' => $category->categoryDescription->name,
                'url' => $url,
                'products' => $this->getProductsByProductsCategories($subCategoriesIds),
            ];
        }

        $currency = [];
        foreach (Currency::all() as $item) {
            $currency[$item->code] = $item->toArray();
        }

        return [
            'categories' => $resultCategories,
            'currency' => $currency,
        ];
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
        /** @var Collection|Product[] $products */
        $products = Product::whereIn('product_id', $this->getColumn($productCategories, 'product_id'))
            ->orderBy('sort_order')
            ->with('productDescription')
            ->where('price', '>', 0.0)
            ->get();

        $result = [];
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
                        $url = sprintf("{$this->siteUrl}/%s/%s", $categoryUrl, $this->seoUrls[$key]);
                    }
                }
            }

            if (null === $url) {
                $url = "{$this->siteUrl}/index.php?route=product/product&product_id={$product->product_id}";
            }

            $item = [
                'product_id' => $product->product_id,
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
        $pricesById = Store::groupsIds();

        $result = [];
        foreach ($pricesById as $value) {
            $result[$value] = 0.0;
        }

        /** @var ProductDiscount[]|Collection $discounts */
        $discounts = ProductDiscount::where('product_id', $id)->get();
        foreach ($discounts as $discount) {
            if (false === key_exists($discount->customer_group_id, $pricesById)) {
                continue;
            }

            $result[$pricesById[$discount->customer_group_id]] = round($discount->price, 2);
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
