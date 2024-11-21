<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'start_time', 'end_time', 'status', 'environment_id'];

    public function environment()
    {
        return $this->belongsTo(Environment::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'tasks_users')->withTimestamps();
    }
}
