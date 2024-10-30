<?php

namespace App\Traits;

use UnitEnum;

trait RandomEnumCase
{
    /**
     * Returns a random case from the current Enum.
     * @return UnitEnum
     */
    public static function getRandomCase(): UnitEnum
    {
        $cases = self::cases();
        return $cases[array_rand($cases)];
    }
}
