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
        return $user->can('Read Proforma Invoice');
    }

    public function create(User $user)
    {
        return $user->can('Create Proforma Invoice');
    }

    public function update(User $user, ProformaInvoice $proformaInvoice)
    {
        return $this->isIssuedByMyBranch($user, $proformaInvoice) && $user->can('Update Proforma Invoice');
    }

    public function delete(User $user, ProformaInvoice $proformaInvoice)
    {
        return $this->isIssuedByMyBranch($user, $proformaInvoice) && $user->can('Delete Proforma Invoice');
    }

    public function confirm(User $user, ProformaInvoice $proformaInvoice)
    {
        return $user->can('Convert Proforma Invoice');
    }

    public function cancel(User $user, ProformaInvoice $proformaInvoice)
    {
        return $user->can('Cancel Proforma Invoice');
    }

    public function close(User $user, ProformaInvoice $proformaInvoice)
    {
        return $user->can('Close Proforma Invoice');
    }

    public function restore(User $user, ProformaInvoice $proformaInvoice)
    {
        return $user->can('Restore Proforma Invoice');
    }
}