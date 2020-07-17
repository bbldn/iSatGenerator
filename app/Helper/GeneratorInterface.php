<?php

namespace App\Helper;

interface GeneratorInterface
{
    /**
     * @param array $categories
     * @param int $customerGroupId
     */
    public function generateAndSave(array $categories, int $customerGroupId): void;
}
