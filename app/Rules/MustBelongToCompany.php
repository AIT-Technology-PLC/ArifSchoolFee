<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MustBelongToCompany implements ValidationRule
{
    private $tableName;

    private $column;

    public function __construct($tableName, $column = 'id')
    {
        $this->tableName = $tableName;

        $this->column = $column;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (authUser()->isCallCenter()) {
            return ;
        }

        $exists = DB::table($this->tableName)
            ->when(Schema::hasColumn($this->tableName, 'company_id'), fn($q) => $q->where('company_id', userCompany()->id))
            ->where($this->column, $value)
            ->when($this->tableName == 'warehouses', function ($query) {
                return $query->where('is_active', 1);
            })
            ->exists();

        if (!$exists) {
            $fail('The ' . Str::singular($this->tableName) . ' selected does not belong to your company.');
        }
    }
}
