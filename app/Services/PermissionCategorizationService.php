<?php

namespace App\Services;

class PermissionCategorizationService
{
    private const PERMISSION_CATEGORIES = [
        'gdn' => [
            'label' => 'Delivery Order',
            'feature' => 'Gdn Management',
        ],
        'grn' => [
            'label' => 'Goods Received Note',
            'feature' => 'Grn Management',
        ],
        'transfer' => [
            'label' => 'Transfer',
            'feature' => 'Transfer Management',
        ],
        'damage' => [
            'label' => 'Damage',
            'feature' => 'Damage Management',
        ],
        'adjustment' => [
            'label' => 'Adjustment',
            'feature' => 'Inventory Adjustment',
        ],
        'siv' => [
            'label' => 'Store Issue Voucher',
            'feature' => 'Siv Management',
        ],
        'merchandise' => [
            'label' => 'Merchandise Inventory',
            'feature' => 'Merchandise Inventory',
        ],
        'return' => [
            'label' => 'Return',
            'feature' => 'Return Management',
        ],
        'sale' => [
            'label' => 'Invoice',
            'feature' => 'Sale Management',
        ],
        'proforma invoice' => [
            'label' => 'Proforma Invoice',
            'feature' => 'Proforma Invoice',
        ],
        'reservation' => [
            'label' => 'Reservation',
            'feature' => 'Reservation Management',
        ],
        'purchase' => [
            'label' => 'Purchase',
            'feature' => 'Purchase Management',
        ],
        'po' => [
            'label' => 'Purchase Order',
            'feature' => 'Purchase Order',
        ],
        'product' => [
            'label' => 'Product & Category',
            'feature' => 'Product Management',
        ],
        'warehouse' => [
            'label' => 'Warehouse',
            'feature' => 'Warehouse Management',
        ],
        'employee' => [
            'label' => 'Employee',
            'feature' => 'User Management',
        ],
        'supplier' => [
            'label' => 'Supplier',
            'feature' => 'Supplier Management',
        ],
        'customer' => [
            'label' => 'Customer',
            'feature' => 'Customer Management',
        ],
        'credit' => [
            'label' => 'Credit',
            'feature' => 'Credit Management',
        ],
        'tender' => [
            'label' => 'Tender',
            'feature' => 'Tender Management',
        ],
        'company' => [
            'label' => 'Company',
            'feature' => 'General Settings',
        ],
    ];

    public static function getPermissionsByCategories($permissions)
    {
        $permissionsByCategory = [];

        foreach (array_keys(static::PERMISSION_CATEGORIES) as $key) {

            $permissionsByCategory[$key] = $permissions
                ->filter(function ($permission) use ($key) {
                    return stristr($permission, $key);
                })
                ->toArray();

        }

        return $permissionsByCategory;
    }

    public static function getPermissionCategories()
    {
        return static::PERMISSION_CATEGORIES;
    }
}
