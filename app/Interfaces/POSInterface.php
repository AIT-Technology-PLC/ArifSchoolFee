<?php

namespace App\Interfaces;

interface POSInterface
{
    public function create();

    public function void();

    public function exists();

    public function getStatus();

    public function isVoid();

    public function isPrinted();
}
