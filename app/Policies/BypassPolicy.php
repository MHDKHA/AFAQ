<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BypassPolicy
{
    use HandlesAuthorization;

    // This method runs before any other policy method
    public function before(User $user, $ability)
    {
        // Simply allow everything for everyone
        return true;
    }

    // We need these methods to exist, but they won't be called
    // because "before" will always return true
    public function viewAny(User $user) { return true; }
    public function view(User $user, $model) { return true; }
    public function create(User $user) { return true; }
    public function update(User $user, $model) { return true; }
    public function delete(User $user, $model) { return true; }
    public function restore(User $user, $model) { return true; }
    public function forceDelete(User $user, $model) { return true; }
    public function deleteAny(User $user) { return true; }
    public function restoreAny(User $user) { return true; }
    public function forceDeleteAny(User $user) { return true; }
    public function replicate(User $user, $model) { return true; }
    public function reorder(User $user) { return true; }
}
