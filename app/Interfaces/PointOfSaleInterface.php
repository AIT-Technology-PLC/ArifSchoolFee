<?php

namespace App\Interfaces;

interface PointOfSaleInterface
{
    public function create();

    public function void();

    public function exists();

    public function getFsNumber();

    public function getStatus();

    public function isVoid();

    public function isPrinted();
}
