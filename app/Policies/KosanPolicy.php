<?php

namespace App\Policies;

use App\Models\Penghuni;
use App\Models\kosan;
use Illuminate\Auth\Access\Response;

class KosanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Penghuni $penghuni): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Penghuni $penghuni, kosan $kosan): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Penghuni $penghuni): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Penghuni $penghuni, kosan $kosan): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Penghuni $penghuni, kosan $kosan): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Penghuni $penghuni, kosan $kosan): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Penghuni $penghuni, kosan $kosan): bool
    {
        return false;
    }
}
