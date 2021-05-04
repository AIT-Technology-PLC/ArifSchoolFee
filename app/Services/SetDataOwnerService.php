<?php

namespace App\Services;

use App\Traits\Approvable;

class SetDataOwnerService
{
    use Approvable;

    private static $company = userCompany()->id;

    private static $createdBy = auth()->id;

    private static $updatedBy = auth()->id;

    private static $approvedBy = auth()->id;

    public static function forTransaction()
    {
        return [
            'company_id' => self::$company,
            'created_by' => self::$createdBy,
            'updated_by' => self::$updatedBy,
            'approved_by' => self::$approvedBy,
        ];
    }

    public static function forUpdate()
    {
        return [
            'updated_by' => self::$updatedBy,
        ];
    }

    public static function forNonTransaction()
    {
        return [
            'company_id' => self::$company,
            'created_by' => self::$createdBy,
            'updated_by' => self::$updatedBy,
        ];
    }
}
