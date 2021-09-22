<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueReferenceNum implements Rule
{
    private $tableName, $excludedId;

    public function __construct($tableName, $excludedId = null)
    {
        $this->tableName = $tableName;

        $this->excludedId = $excludedId;
    }

    public function passes($attribute, $value)
    {
        return DB::table($this->tableName)
            ->where('warehouse_id', auth()->user()->warehouse_id)
            ->where('company_id', userCompany()->id)
            ->where('code', $value)
            ->where('id', '<>', $this->excludedId)
            ->doesntExist();
    }

    public function message()
    {
        return 'The reference number has already been taken.';
    }
}
