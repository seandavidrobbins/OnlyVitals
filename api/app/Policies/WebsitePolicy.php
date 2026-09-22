<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Website;

class WebsitePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Website $website): bool
    {
        return $user->is($website->user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Website $website): bool
    {
        return $user->is($website->user);
    }

    public function delete(User $user, Website $website): bool
    {
        return $user->is($website->user);
    }
}
