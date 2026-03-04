<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->admin === true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        return $user->admin === true || $ticket->users->contains($user->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        return $user->admin === true || $ticket->users->contains($user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->admin === true || $ticket->users->contains($user->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    // Nincs ilyen feature implementálva
    // public function restore(User $user, Ticket $ticket): bool
    // {
    //     return $user->admin === true || $ticket->users->contains($user->id);
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // Nincs ilyen feature implementálva
    // public function forceDelete(User $user, Ticket $ticket): bool
    // {
    //     return $user->admin === true || $ticket->users->contains($user->id);
    // }
}
