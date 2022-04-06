<?php

namespace App\Policies;

use App\Models\PadPermission;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user, Pad $pad)
    {
        return PadPermission::where('pad_id', $pad->id)->where('name', 'Read')->exists();
    }

    public function view(User $user, Pad $pad, Transaction $transaction)
    {
        return PadPermission::where('pad_id', $pad->id)->where('name', 'Read')->exists();
    }

    public function create(User $user, Pad $pad)
    {
        return PadPermission::where('pad_id', $pad->id)->where('name', 'Create')->exists();
    }

    public function update(User $user, Pad $pad, Transaction $transaction)
    {
        return $this->isIssuedByMyBranch($user, $transaction) &&
        PadPermission::where('pad_id', $pad->id)->where('name', 'Update')->exists();
    }

    public function delete(User $user, Pad $pad, Transaction $transaction)
    {
        return $this->isIssuedByMyBranch($user, $transaction) &&
        PadPermission::where('pad_id', $pad->id)->where('name', 'Delete')->exists();
    }

    public function approve(User $user, Pad $pad, Transaction $transaction)
    {
        return PadPermission::where('pad_id', $pad->id)->where('name', 'Approve')->exists();
    }

    public function subtract(User $user, Pad $pad, Transaction $transaction)
    {
        return PadPermission::where('pad_id', $pad->id)->where('name', 'Subtract')->exists();
    }

    public function add(User $user, Pad $pad, Transaction $transaction)
    {
        return PadPermission::where('pad_id', $pad->id)->where('name', 'Add')->exists();
    }

    public function close(User $user, Pad $pad, Transaction $transaction)
    {
        return PadPermission::where('pad_id', $pad->id)->where('name', 'Close')->exists();
    }

    public function cancel(User $user, Pad $pad, Transaction $transaction)
    {
        return PadPermission::where('pad_id', $pad->id)->where('name', 'Cancel')->exists();
    }
}
