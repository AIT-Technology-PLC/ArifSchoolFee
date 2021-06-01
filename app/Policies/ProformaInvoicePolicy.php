<?php

namespace App\Policies;

use App\Models\ProformaInvoice;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProformaInvoicePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Proforma Invoice');
    }

    public function view(User $user, ProformaInvoice $proformaInvoice)
    {
        $doesProformaInvoiceBelongToMyCompany = $user->employee->company_id == $proformaInvoice->company_id;

        return $doesProformaInvoiceBelongToMyCompany && $user->can('Read Proforma Invoice');
    }

    public function create(User $user)
    {
        return $user->can('Create Proforma Invoice');
    }

    public function update(User $user, ProformaInvoice $proformaInvoice)
    {
        $doesProformaInvoiceBelongToMyCompany = $user->employee->company_id == $proformaInvoice->company_id;

        return $doesProformaInvoiceBelongToMyCompany && $user->can('Update Proforma Invoice');
    }

    public function delete(User $user, ProformaInvoice $proformaInvoice)
    {
        $doesProformaInvoiceBelongToMyCompany = $user->employee->company_id == $proformaInvoice->company_id;

        return $doesProformaInvoiceBelongToMyCompany && $user->can('Delete Proforma Invoice');
    }

    public function approve(User $user, ProformaInvoice $proformaInvoice)
    {
        $doesProformaInvoiceBelongToMyCompany = $user->employee->company_id == $proformaInvoice->company_id;

        return $doesProformaInvoiceBelongToMyCompany && $user->can('Approve Proforma Invoice');
    }

    public function execute(User $user, ProformaInvoice $proformaInvoice)
    {
        $doesProformaInvoiceBelongToMyCompany = $user->employee->company_id == $proformaInvoice->company_id;

        return $doesProformaInvoiceBelongToMyCompany && $user->can('Execute Proforma Invoice');
    }
}
