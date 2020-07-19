<?php

namespace App\Helper;

interface GeneratorInterface
{
    /**
     * @param array $data
     * @param int $customerGroupId
     */
    public function generateAndSave(array $data, int $customerGroupId): void;
}
