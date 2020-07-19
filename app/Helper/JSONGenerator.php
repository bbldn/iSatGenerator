<?php

namespace App\Helper;

use Illuminate\Support\Facades\Storage;

class JSONGenerator implements GeneratorInterface
{
    /**
     * @param array $data
     * @param int $customerGroupId
     */
    public function generateAndSave(array $data, int $customerGroupId): void
    {
        if ($customerGroupId > 1) {
            $currency = $data['currency']['USD'];
        } else {
            $currency = $data['currency']['UAH'];
        }
        unset($data['currency']);

        $pricesForDo = [];
        $pricesForRemove = Store::groupsIds();
        foreach ($pricesForRemove as $id => $_) {
            if ($id === $customerGroupId || $id === Store::defaultGroupId()) {
                $pricesForDo[] = $pricesForRemove[$id];
                unset($pricesForRemove[$id]);
            }
        }

        foreach ($data['categories'] as $i => $category) {
            foreach ($category['products'] as $j => $product) {
                foreach ($pricesForDo as $price) {
                    if (true === key_exists($price, $product)) {
                        $product[$price] = sprintf(
                            '%s%s%s',
                            $currency['symbol_left'],
                            round((float)$product[$price] * (float)$currency['value'], (int)$currency['decimal_place']),
                            $currency['symbol_right']
                        );
                    }
                }

                foreach ($pricesForRemove as $price) {
                    if (true === key_exists($price, $product)) {
                        unset($product[$price]);
                    }
                }
                $category['products'][$j] = $product;
            }
            $data['categories'][$i] = $category;
        }

        Storage::put("{$customerGroupId}.json", json_encode($data['categories']));
    }
}
