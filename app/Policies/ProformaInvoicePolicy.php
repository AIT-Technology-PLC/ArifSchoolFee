<?php

namespace App\Policies;

use App\Models\ProformaInvoice;
use App\Traits\ModelToCompanyBelongingnessChecker;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProformaInvoicePolicy
{
    use HandlesAuthorization, ModelToCompanyBelongingnessChecker;

    public function viewAny(User $user)
    {
        return $user->can('Read Proforma Invoice');
    }

    public function view(User $user, ProformaInvoice $proformaInvoice)
    {
        return $this->doesModelBelongToMyCompany($user, $proformaInvoice) && $user->can('Read Proforma Invoice');
    }

    public function create(User $user)
    {
        return $user->can('Create Proforma Invoice');
    }

    public function update(User $user, ProformaInvoice $proformaInvoice)
    {
        return $this->doesModelBelongToMyCompany($user, $proformaInvoice) && $user->can('Update Proforma Invoice');
    }

    public function delete(User $user, ProformaInvoice $proformaInvoice)
    {
        return $this->doesModelBelongToMyCompany($user, $proformaInvoice) && $user->can('Delete Proforma Invoice');
    }

    public function convert(User $user, ProformaInvoice $proformaInvoice)
    {
        return $this->doesModelBelongToMyCompany($user, $proformaInvoice) && $user->can('Convert Proforma Invoice');
    }

    public function cancel(User $user, ProformaInvoice $proformaInvoice)
    {
        return $this->doesModelBelongToMyCompany($user, $proformaInvoice) && $user->can('Cancel Proforma Invoice');
    }
}
