<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = ['board_id', 'email', 'token'];

    /**
     * Get the board that the invitation belongs to.
     */
    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
