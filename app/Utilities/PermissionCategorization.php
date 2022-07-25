<?php

namespace App\Utilities;

class PermissionCategorization
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
        'inventory' => [
            'label' => 'Inventory',
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
        'price' => [
            'label' => 'Price',
            'feature' => 'Price Management',
        ],
        'tender' => [
            'label' => 'Tender',
            'feature' => 'Tender Management',
        ],
        'pad' => [
            'label' => 'Pad',
            'feature' => 'Pad Management',
        ],
        'company' => [
            'label' => 'Company',
            'feature' => 'General Settings',
        ],
        'bom' => [
            'label' => 'Bill Of Material',
            'feature' => 'Bill Of Material Management',
        ],
        'job' => [
            'label' => 'Job',
            'feature' => 'Job Management',
        ],
        'employee transfer' => [
            'label' => 'Employee Transfer',
            'feature' => 'Employee Transfer',
        ],
        'warning' => [
            'label' => 'Warning',
            'feature' => 'Warning Management',
        ],
        'advancement' => [
            'label' => 'Advancement',
            'feature' => 'Advancement Management',
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
