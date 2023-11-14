<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanyResetAccountRequest;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class CompanyResetAccountController extends Controller
{
    public function __invoke(CompanyResetAccountRequest $request, Company $company)
    {
        if (!$company->isInTraining()) {
            return back()->with('failedMessage', 'Account is in LIVE mode and can not be reset.');
        }

        DB::transaction(function () use ($request, $company) {
            if ($request->validated('reset_inventory')) {
                $this->resetInventory($company);
            }

            if ($request->validated('reset_finance')) {
                $this->resetFinance($company);
            }

            if ($request->validated('reset_pad')) {
                $this->resetPad($company);
            }

            $this->resetByTables($company, $request->validated('tables'));
        });

        return back()->with('successMessage', 'Account has been resetted as ordered.');
    }

    private function resetInventory($company)
    {
        $company->grns()->forceDelete();

        $company->gdns()->forceDelete();

        $company->sales()->forceDelete();

        $company->transfers()->forceDelete();

        $company->damages()->forceDelete();

        $company->returns()->forceDelete();

        $company->adjustments()->forceDelete();

        $company->reservations()->forceDelete();

        $company->transactions()->whereRelation('pad', 'inventory_operation_type', '<>', 'none')->forceDelete();

        $company->jobs()->forceDelete();

        $company->sivs()->forceDelete();

        $company->merchandises()->forceDelete();

        $company->inventoryHistories()->forceDelete();

        $company->inventoryValuationBalances()->forceDelete();

        $company->inventoryValuationHistories()->forceDelete();

        $company->products()->update([
            'average_unit_cost' => 0,
            'fifo_unit_cost' => 0,
            'lifo_unit_cost' => 0,
        ]);
    }

    private function resetFinance($company)
    {
        $company->credits()->forceDelete();

        $company->debts()->forceDelete();

        $company->customerDeposits()->forceDelete();

        $company->customers()->update(['balance' => 0]);
    }

    private function resetPad($company)
    {
        $company->transactions()->whereRelation('pad', 'inventory_operation_type', 'none')->forceDelete();
    }

    private function resetByTables($company, $tables)
    {
        if (!is_array($tables)) {
            return;
        }

        foreach ($tables as $table) {
            DB::table($table)->where('company_id', $company->id)->delete();
        }
    }
}
