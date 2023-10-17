<?php

namespace App\Utilities;

class PermissionCategorization
{
    private const PERMISSION_CATEGORIES = [
        'gdn' => [
            'label' => 'Delivery Order',
            'feature' => 'Gdn Management',
            'include' => ['Read Sale Report'],
        ],
        'grn' => [
            'label' => 'Goods Received Note',
            'feature' => 'Grn Management',
        ],
        'transfer' => [
            'label' => 'Transfer',
            'feature' => 'Transfer Management',
            'exclude' => ['Employee'],
        ],
        'damage' => [
            'label' => 'Damage',
            'feature' => 'Damage Management',
        ],
        'adjustment' => [
            'label' => 'Adjustment',
            'feature' => 'Inventory Adjustment',
            'exclude' => ['Compensation Adjustment'],
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
            'include' => ['Read Profit Report'],
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
            'exclude' => ['Employee Transfer'],
        ],
        'supplier' => [
            'label' => 'Supplier',
            'feature' => 'Supplier Management',
        ],
        'customer' => [
            'label' => 'Customer',
            'feature' => 'Customer Management',
            'exclude' => ['Customer Deposit'],
        ],
        'credit' => [
            'label' => 'Credit',
            'feature' => 'Credit Management',
        ],
        'price' => [
            'label' => 'Price',
            'feature' => 'Price Management',
            'exclude' => ['Price Increment'],
        ],
        'tender' => [
            'label' => 'Tender',
            'feature' => 'Tender Management',
        ],
        'pad' => [
            'label' => 'Pad',
            'feature' => 'Pad Management',
        ],
        'custom field' => [
            'label' => 'Custom Field Manager',
            'feature' => 'Custom Field Management',
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
        'department' => [
            'label' => 'Department',
            'feature' => 'Department Management',
        ],
        'employee transfer' => [
            'label' => 'Employee Transfer',
            'feature' => 'Employee Transfer',
        ],
        'warning' => [
            'label' => 'Warning',
            'feature' => 'Warning Management',
        ],
        'attendance' => [
            'label' => 'Attendance',
            'feature' => 'Attendance Management',
        ],
        'leave' => [
            'label' => 'Leave',
            'feature' => 'Leave Management',
        ],
        'advancement' => [
            'label' => 'Advancement',
            'feature' => 'Advancement Management',
        ],
        'expense claim' => [
            'label' => 'Expense Claim',
            'feature' => 'Expense Claim',
        ],
        'announcement' => [
            'label' => 'Announcement',
            'feature' => 'Announcement Management',
        ],
        'compensation' => [
            'label' => 'Compensation',
            'feature' => 'Compensation Management',
            'exclude' => ['Compensation Adjustment'],
        ],
        'compensation adjustment' => [
            'label' => 'Compensation Adjustment',
            'feature' => 'Compensation Adjustment',
        ],
        'debt' => [
            'label' => 'Debt',
            'feature' => 'Debt Management',
        ],
        'expense' => [
            'label' => 'Expense',
            'feature' => 'Expense Management',
            'exclude' => ['Expense Claim'],
        ],
        'contact' => [
            'label' => 'Contact',
            'feature' => 'Contact Management',
        ],
        'price increment' => [
            'label' => 'Price Increment',
            'feature' => 'Price Increment',
        ],
        'brand' => [
            'label' => 'Brand',
            'feature' => 'Brand Management',
        ],
        'payroll' => [
            'label' => 'Payroll',
            'feature' => 'Payroll Management',
        ],
        'customer deposit' => [
            'label' => 'Customer Deposit',
            'feature' => 'Customer Deposit Management',
        ],
        'cost update' => [
            'label' => 'Cost Update',
            'feature' => 'Cost Update Management',
        ],
        'merchandise batch' => [
            'label' => 'Batch',
            'feature' => 'Batch Management',
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
                ->reject(function ($permission) use ($key) {
                    if (!isset(static::PERMISSION_CATEGORIES[$key]['exclude'])) {
                        return;
                    }

                    return str($permission)->containsAll(static::PERMISSION_CATEGORIES[$key]['exclude']);
                })
                ->toArray();

            if (isset(static::PERMISSION_CATEGORIES[$key]['include'])) {
                array_push($permissionsByCategory[$key], ...static::PERMISSION_CATEGORIES[$key]['include']);
            }
        }

        return $permissionsByCategory;
    }

    public static function getPermissionCategories()
    {
        return static::PERMISSION_CATEGORIES;
    }
}
