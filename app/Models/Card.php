<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'list_id'];

    public function list()
    {
        return $this->belongsTo(TaskList::class);
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'card_user');
    }
}
