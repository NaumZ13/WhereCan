<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function review()
    {
       return $this->belongsTo(Review::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function canBeEditedOrDeleted()
    {
        $timeLimit = now()->subHours(2);
        return $this->created_at >= $timeLimit;
    }
}