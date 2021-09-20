<?php

namespace App\Services;

use Illuminate\Support\Arr;

class SetDataOwnerService
{
    private $attributes;

    private static $instance = null;

    private function __construct()
    {
        $this->attributes = [
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'warehouse_id' => auth()->user()->warehouse_id,
        ];
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function forUpdate()
    {
        return Arr::only(self::getInstance()->attributes, 'updated_by');
    }

    public static function forNonTransaction()
    {
        return Arr::only(self::getInstance()->attributes, ['created_by', 'updated_by']);
    }

    public static function forTransaction()
    {
        return Arr::only(self::getInstance()->attributes, ['created_by', 'updated_by', 'warehouse_id']);
    }
}
