<?php

namespace App\Policies;

use App\Models\Evento;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventoPolicy
{
    /*public function before(User $user, string $ability)
    {
        if( $user->role_id==1){
            return true;
        }
    }
    public function store(User $user):bool{
        return false;
    }
    public function actualizar(User $user, Evento $evento):bool{

        return false;
    }
    public function unirse(User $user, Evento $evento):bool{

        return true;
    }
    public function eliminar(User $user, Evento $evento):bool{

        return false;
    }
    public function unirse(User $user, Evento $evento):bool{

        return true;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Evento $evento): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Evento $evento): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Evento $evento): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Evento $evento): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
   /public function forceDelete(User $user, Evento $evento): bool
    {
        //
    }
}
