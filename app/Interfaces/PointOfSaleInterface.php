<?php

namespace App\Interfaces;

interface PointOfSaleInterface
{
    public function create();

    public function getFsNumber();

    public function isVoid();

    public function exists();
}
