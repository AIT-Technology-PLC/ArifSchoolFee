<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MustBelongToCompany implements Rule
{
    private $tableName;

    public function __construct($tableName)
    {
        $this->tableName = $tableName;
    }

    public function passes($attribute, $value)
    {
        return DB::table($this->tableName)
            ->where('company_id', userCompany()->id)
            ->where('id', $value)
            ->when($this->tableName == 'warehouses', fn($query) => $query->where('is_active', 1))
            ->exists();
    }

    public function message()
    {
        return 'The ' . Str::singular($this->tableName) . ' selected does not belong to your company.';
    }
}
