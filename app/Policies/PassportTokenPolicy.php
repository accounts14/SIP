<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class PassportTokenPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view($user, $token)
    {
        return $user->id === $token->user_id;
    }
    
}