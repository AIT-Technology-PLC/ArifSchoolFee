<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\EmployeeTransferDetail;

class EmployeeTransferDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Employee Transfer');
    }

    public function destroy(EmployeeTransferDetail $employeeTransferDetail)
    {
        $this->authorize('delete', $employeeTransferDetail->employeeTransfer);

        $employeeTransferDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
