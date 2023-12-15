<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UniqueReferenceNum implements ValidationRule
{
    private $tableName;

    private $excludedId;

    private $value;

    public function __construct($tableName, $excludedId = null)
    {
        $this->tableName = $tableName;

        $this->excludedId = $excludedId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $this->value = round($value);

        $isTaken = DB::table($this->tableName)
            ->when(Schema::hasColumn($this->tableName, 'warehouse_id'), fn($q) => $q->where('warehouse_id', authUser()->warehouse_id))
            ->where('company_id', userCompany()->id)
            ->where('code', $value)
            ->when(is_numeric($this->excludedId), fn($q) => $q->where('id', '<>', $this->excludedId))
            ->when(is_countable($this->excludedId), fn($q) => $q->whereNotIn('id', $this->excludedId))
            ->exists();

        if ($isTaken) {
            $fail("Reference #{$this->value} has already been taken.");
        }
    }
}
