<?php

namespace App\Helper;

class StoreContext
{
    /**
     * @return int
     */
    public static function defaultGroupId(): int
    {
        return 1;
    }

    /**
     * @return array<int, string>
     */
    public static function groupsIds(): array
    {
        return [
            1 => 'retail',
            2 => 'dealer',
            4 => 'partner',
            3 => 'wholesale',
        ];
    }
}
