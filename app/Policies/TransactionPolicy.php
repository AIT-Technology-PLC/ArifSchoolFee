<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user, $pad)
    {
        return $user->hasRole('System Manager') ||
        $user->padPermissions()->where('pad_id', $pad->id)->where('name', 'Read')->exists();
    }

    public function view(User $user, Transaction $transaction)
    {
        return $user->hasRole('System Manager') ||
        $user->padPermissions()->where('pad_id', $transaction->pad_id)->where('name', 'Read')->exists();
    }

    public function create(User $user, $pad)
    {
        return $user->hasRole('System Manager') ||
        $user->padPermissions()->where('pad_id', $pad->id)->where('name', 'Create')->exists();
    }

    public function update(User $user, Transaction $transaction)
    {
        return $user->hasRole('System Manager') ||
            ($this->isIssuedByMyBranch($user, $transaction) &&
            $user->padPermissions()->where('pad_id', $transaction->pad_id)->where('name', 'Update')->exists());
    }

    public function delete(User $user, Transaction $transaction)
    {
        return $user->hasRole('System Manager') ||
            ($this->isIssuedByMyBranch($user, $transaction) &&
            $user->padPermissions()->where('pad_id', $transaction->pad_id)->where('name', 'Delete')->exists());
    }

    public function approve(User $user, Transaction $transaction)
    {
        return $user->hasRole('System Manager') ||
        $user->padPermissions()->where('pad_id', $transaction->pad_id)->where('name', 'Approve')->exists();
    }

    public function subtract(User $user, Transaction $transaction)
    {
        return $user->hasRole('System Manager') ||
        $user->padPermissions()->where('pad_id', $transaction->pad_id)->where('name', 'Subtract')->exists();
    }

    public function add(User $user, Transaction $transaction)
    {
        return $user->hasRole('System Manager') ||
        $user->padPermissions()->where('pad_id', $transaction->pad_id)->where('name', 'Add')->exists();
    }

    public function convert(User $user, Transaction $transaction)
    {
        return $user->hasRole('System Manager') ||
        $user->padPermissions()->where('pad_id', $transaction->pad_id)->where('name', 'Convert')->exists();
    }
}
