<?php

namespace App\Policies;

use App\Models\ProformaInvoice;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProformaInvoicePolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read Proforma Invoice');
    }

    public function view(User $user, ProformaInvoice $proformaInvoice)
    {
        return $this->isIssuedByMyCompany($user, $proformaInvoice) && $user->can('Read Proforma Invoice');
    }

    public function create(User $user)
    {
        return $user->can('Create Proforma Invoice');
    }

    public function update(User $user, ProformaInvoice $proformaInvoice)
    {
        return $this->isIssuedByMyCompany($user, $proformaInvoice, true) && $user->can('Update Proforma Invoice');
    }

    public function delete(User $user, ProformaInvoice $proformaInvoice)
    {
        return $this->isIssuedByMyCompany($user, $proformaInvoice, true) && $user->can('Delete Proforma Invoice');
    }

    public function convert(User $user, ProformaInvoice $proformaInvoice)
    {
        return $this->isIssuedByMyCompany($user, $proformaInvoice) && $user->can('Convert Proforma Invoice');
    }

    public function cancel(User $user, ProformaInvoice $proformaInvoice)
    {
        return $this->isIssuedByMyCompany($user, $proformaInvoice) && $user->can('Cancel Proforma Invoice');
    }

    public function close(User $user, ProformaInvoice $proformaInvoice)
    {
        return $this->isIssuedByMyCompany($user, $proformaInvoice) && $user->can('Close Proforma Invoice');
    }
}
