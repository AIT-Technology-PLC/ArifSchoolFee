<?php

namespace App\Services;

use App\Traits\Approvable;

class SetDataOwnerService
{
    use Approvable;

    private $company;

    private $createdBy;

    private $updatedBy;

    private $approvedBy;

    private function __construct()
    {
        $this->company = userCompany()->id;

        $this->createdBy = auth()->id();

        $this->updatedBy = auth()->id();

        $this->approvedBy = $this->approvedBy();
    }

    public static function forTransaction()
    {
        return [
            'company_id' => (new self)->company,
            'created_by' => (new self)->createdBy,
            'updated_by' => (new self)->updatedBy,
            'approved_by' => (new self)->approvedBy,
        ];
    }

    public static function forUpdate()
    {
        return [
            'updated_by' => (new self)->updatedBy,
        ];
    }

    public static function forNonTransaction()
    {
        return [
            'company_id' => (new self)->company,
            'created_by' => (new self)->createdBy,
            'updated_by' => (new self)->updatedBy,
        ];
    }
}
