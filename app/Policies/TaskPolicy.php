<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{

    use HandlesAuthorization;

    public function update(User $user, Task $task)
    {
        return $task->users->contains($user) || $user->id === $task->environment->user_id;
    }
}
