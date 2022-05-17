<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Customer([
            'company_name' => $row['company_name'],
            'tin' => $row['tin'],
            'address' => $row['address'],
            'contact_name' => $row['contact_name'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'country' => $row['country'],
            'credit_amount_limit' => $row['credit_amount_limit'],
        ]);
    }
}