<?php

namespace App\Interfaces;

interface DetailHistoryServiceInterface
{
    public static function get($warehouse, $product);

    public static function format($grnDetails);

    public static function formatted($warehouse, $product);

}
