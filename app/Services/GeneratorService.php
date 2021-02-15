<?php

namespace App\Services;

use App\Category;
use App\Currency;
use App\Helper\StoreContext;
use App\ProductCategory;
use App\ProductDiscontinued;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductCategoryRepository;
use App\Repositories\ProductDiscountRepository;
use App\Repositories\ProductRepository;
use App\SeoUrl;
use Illuminate\Support\Collection;

class GeneratorService
{
    private string $siteUrl;

    private ?array $seoUrls = null;

    private ?array $productsDiscontinued = null;

    private ProductRepository $productRepository;

    private CategoryRepository $categoryRepository;

    private ProductCategoryRepository $productCategoryRepository;

    private ProductDiscountRepository $productDiscountRepository;

    /**
     * GeneratorService constructor.
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     * @param ProductCategoryRepository $productCategoryRepository
     * @param ProductDiscountRepository $productDiscountRepository
     */
    public function __construct(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        ProductCategoryRepository $productCategoryRepository,
        ProductDiscountRepository $productDiscountRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productCategoryRepository = $productCategoryRepository;
        $this->productDiscountRepository = $productDiscountRepository;
    }

    /**
     * @param string $siteUrl
     * @return GeneratorService
     */
    public function setSiteUrl(string $siteUrl): self
    {
        $this->siteUrl = $siteUrl;

        return $this;
    }

    /**
     *
     */
    public function init(): void
    {
        if (null === $this->seoUrls) {
            $this->seoUrls = [];
            foreach (SeoUrl::all() as $seoUrl) {
                $this->seoUrls[$seoUrl->query] = $seoUrl->keyword;
            }
        }

        if (null === $this->productsDiscontinued) {
            $this->productsDiscontinued = [];
            foreach (ProductDiscontinued::all() as $productDiscontinued) {
                $this->productsDiscontinued[$productDiscontinued->product_id] = null;
            }
        }
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $mainCategories = $this->categoryRepository->findParentCategoriesWithCategoryDescription();

        $resultCategories = [];
        foreach ($mainCategories as $category) {
            if (null === $category->categoryDescription) {
                continue;
            }

            $categories = $this->categoryRepository->findByParentId($category->category_id)->push($category);
            $categoriesIds = array_map(fn(Category $category) => $category->category_id, $categories);

            $subCategoriesIds = $this->productCategoryRepository->findByCategoryIds($categoriesIds);

            $key = "category_id={$category->category_id}";
            if (true === key_exists($key, $this->seoUrls)) {
                $url = sprintf("{$this->siteUrl}/%s", $this->seoUrls[$key]);
            } else {
                $url = "{$this->siteUrl}/index.php?route=product/category&category_id={$category->category_id}";
            }

            $resultCategories[] = [
                'url' => $url,
                'category_id' => $category->category_id,
                'name' => $category->categoryDescription->name,
                'products' => $this->getProductsByProductsCategories($subCategoriesIds),
            ];
        }

        $currency = array_map(fn(Currency $currency) => $currency->toArray(), Currency::all());

        return [
            'currency' => $currency,
            'categories' => $resultCategories,
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
            $this->seoUrls[$seoUrl->query] = $seoUrl->keyword;
        }
    }

    /**
     * @param Collection|ProductCategory[] $productCategories
     * @return array
     */
    protected function getProductsByProductsCategories(Collection $productCategories): array
    {
        $productIds = [];
        $mainCategories = [];

        foreach ($productCategories as $item) {
            $productIds[] = $item->product_id;
            if (true === $item->main_category) {
                $mainCategories[$item->product_id] = $item->category_id;
            }
        }

        $productIds = array_map(fn(ProductCategory $productCategory) => $productCategory->product_id, $productCategories);
        $products = $this->productRepository->findByIdsAndPrice($productIds, 0.0);

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
                    $query = "category_id={$mainCategories[$product->product_id]}";

                    if (true === key_exists($query, $this->seoUrls)) {
                        $categoryUrl = $this->seoUrls[$query];
                        $url = "{$this->siteUrl}/{$categoryUrl}/{$this->seoUrls[$key]}";
                    }
                }
            }

            if (null === $url) {
                $url = "{$this->siteUrl}/index.php?route=product/product&product_id={$product->product_id}";
            }

            $item = [
                'url' => $url,
                'product_id' => $product->product_id,
                'name' => $product->productDescription->name,
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
        $pricesById = StoreContext::groupsIds();

        $result = [];
        foreach ($pricesById as $value) {
            $result[$value] = 0.0;
        }

        $discounts = $this->productDiscountRepository->findByProductId($id);
        foreach ($discounts as $discount) {
            if (false === key_exists($discount->customer_group_id, $pricesById)) {
                continue;
            }

            $result[$pricesById[$discount->customer_group_id]] = round($discount->price, 2);
        }

        return $result;
    }
}
