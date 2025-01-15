<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueReferenceNum implements ValidationRule
{
    private $tableName;

    private $excludedId;

    public function __construct($tableName, $excludedId = null)
    {
        $this->tableName = $tableName;

        $this->excludedId = $excludedId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $numericValue = is_numeric($value) ? round((float) $value) : $value;

        $isTaken = DB::table($this->tableName)
            ->where('company_id', userCompany()->id)
            ->where('code', $value)
            ->when(is_numeric($this->excludedId), fn($q) => $q->where('id', '<>', $this->excludedId))
            ->when(is_countable($this->excludedId), fn($q) => $q->whereNotIn('id', $this->excludedId))
            ->exists();

        if ($isTaken) {
            $fail("Reference #{$numericValue} has already been taken.");
        }
    }
}
