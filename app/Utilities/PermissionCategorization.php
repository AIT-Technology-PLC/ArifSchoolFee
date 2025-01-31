<?php

namespace App\Utilities;

class PermissionCategorization
{
    private const PERMISSION_CATEGORIES = [
        'branch' => [
            'label' => 'Branch',
            'feature' => 'Branch Management',
        ],

        'academic year' => [
            'label' => 'Academic Year',
            'feature' => 'Academic Year',
        ],

        'section' => [
            'label' => 'Section',
            'feature' => 'Section Management',
        ],

        'class' => [
            'label' => 'Class',
            'feature' => 'Class Management',
        ],

        'route' => [
            'label' => 'Route',
            'feature' => 'Route Management',
        ],

        'vehicle' => [
            'label' => 'Vehicle',
            'feature' => 'Vehicle Management',
        ],

        'student category' => [
            'label' => 'Student Category',
            'feature' => 'Student Category',
        ],

        'student group' => [
            'label' => 'Student Group',
            'feature' => 'Student Group',
        ],

        'student' => [
            'label' => 'Student',
            'feature' => 'Student Management',
            'exclude' => ['Student Group', 'Student Category', 'Student Promote'],
        ],

        'student promote' => [
            'label' => 'Student Promote',
            'feature' => 'Student Promote',
        ],
        
        'department' => [
            'label' => 'Department',
            'feature' => 'Department Management',
        ],

        'designation' => [
            'label' => 'Designation',
            'feature' => 'Designation Management',
        ],

        'staff' => [
            'label' => 'Staff',
            'feature' => 'Staff Management',
        ],

        'account' => [
            'label' => 'Account Management',
            'feature' => 'Account Management',
        ],

        'fee group' => [
            'label' => 'Fee Group',
            'feature' => 'Fee Group',
        ],

        'fee type' => [
            'label' => 'Fee Type',
            'feature' => 'Fee Type',
        ],

        'fee discount' => [
            'label' => 'Fee Discount',
            'feature' => 'Fee Discount',
        ],

        'fee master' => [
            'label' => 'Fee Mater',
            'feature' => 'Fee Master',
        ],

        'collect fee' => [
            'label' => 'Collect Fee',
            'feature' => 'Collect Fee',
            'include' => ['Search Fee Payment', 'Search Fee Due'],
        ],

        'message' => [
            'label' => 'Email/SMS',
            'feature' => 'Email/SMS Management',
        ],

        'notice' => [
            'label' => 'Notice',
            'feature' => 'Notice Management',
        ],

        'employee' => [
            'label' => 'User',
            'feature' => 'User Management',
        ],

        'log' => [
            'label' => 'Log',
            'feature' => 'Log Management',
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
                ->reject(function ($permission) use ($key) {
                    if (!isset(static::PERMISSION_CATEGORIES[$key]['exclude'])) {
                        return;
                    }

                    return str($permission)->contains(static::PERMISSION_CATEGORIES[$key]['exclude']);
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
