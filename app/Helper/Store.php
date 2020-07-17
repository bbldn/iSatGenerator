<?php

namespace App\Helper;

class Store
{
    /**
     * @return int
     */
    public static function defaultGroupId(): int
    {
        return 1;
    }

    /**
     * @return array
     */
    public static function groupsIds(): array
    {
        return [1 => 'retail', 2 => 'dealer', 3 => 'wholesale', 4 => 'partner',];
    }
}
