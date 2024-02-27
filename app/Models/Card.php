<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'task_list_id', 'description', 'labels', 'priority', 'user_id'];

    public function taskList()
    {
        return $this->belongsTo(TaskList::class);
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'card_user');
    }
}
