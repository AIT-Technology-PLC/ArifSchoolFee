<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MustBelongToCompany implements Rule
{
    private $tableName, $column;

    public function __construct($tableName, $column = 'id')
    {
        $this->tableName = $tableName;

        $this->column = $column;
    }

    public function passes($attribute, $value)
    {
        if ($this->tableName == 'products' && str_contains($attribute, 'proformaInvoice') && !is_int($value)) {
            return true;
        }

        return DB::table($this->tableName)
            ->where('company_id', userCompany()->id)
            ->where($this->column, $value)
            ->when($this->tableName == 'warehouses', fn($query) => $query->where('is_active', 1))
            ->exists();
    }

    public function message()
    {
        return 'The ' . Str::singular($this->tableName) . ' selected does not belong to your company.';
    }
}
