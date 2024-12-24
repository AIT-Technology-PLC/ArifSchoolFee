<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\AccountDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Account Management');

        $this->authorizeResource(Account::class, 'account');
    }

    public function index(AccountDatatable $datatable)
    {
        $datatable->builder()->setTableId('accounts-datatable')->orderBy(1, 'asc');

        $totalAccount = Account::count();

        $totalActiveAccount = Account::active()->count();

        $totalInActiveAccount = $totalAccount - $totalActiveAccount;

        return $datatable->render('accounts.index', compact('totalAccount', 'totalActiveAccount', 'totalInActiveAccount'));
    }

    public function create()
    {
        return view('accounts.create');
    }

    public function store(StoreAccountRequest $request)
    {
        Account::firstOrCreate(
            $request->safe()->only(['account_type'] + ['company_id' => userCompany()->id]),
            $request->safe()->except(['account_type'] + ['company_id' => userCompany()->id]),
        );

        return redirect()->route('accounts.index')->with('successMessage', 'New Account Created Successfully.');
    }

    public function edit(Account $account)
    {
        return view('accounts.edit',  compact('account'));
    }

    public function update(UpdateAccountRequest $request, Account $account)
    {
        $account->update($request->validated());

        return redirect()->route('accounts.index')->with('successMessage', 'Updated Successfully.');
    }
}
