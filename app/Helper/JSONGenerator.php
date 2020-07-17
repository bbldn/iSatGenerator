<?php

namespace App\Helper;

use Illuminate\Support\Facades\Storage;

class JSONGenerator implements GeneratorInterface
{
    /**
     * @param array $categories
     * @param int $customerGroupId
     */
    public function generateAndSave(array $categories, int $customerGroupId): void
    {
        $pricesById = Store::groupsIds();

        unset($pricesById[$customerGroupId]);
        if ($customerGroupId > Store::defaultGroupId()) {
            unset($pricesById[Store::defaultGroupId()]);
        }

        foreach ($categories as &$category) {
            foreach ($category['products'] as &$product) {
                foreach ($pricesById as $price) {
                    unset($product[$price]);
                }
            }
        }

        Storage::put("{$customerGroupId}.json", json_encode($categories));
    }
}
