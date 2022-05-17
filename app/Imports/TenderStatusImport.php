<?php

namespace App\Imports;

use App\Models\TenderStatus;
use Maatwebsite\Excel\Concerns\ToModel;

class TenderStatusImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new TenderStatus([
            'status' => $row['status'],
        ]);
    }
}