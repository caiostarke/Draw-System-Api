<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Draw extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "name",
        "image",
        "body_content"
    ];

    protected $appends = ['image_url', 'likes_count'];

    public function getImageUrlAttribute() {
        return Storage::url("imgs/" . $this->image);
    }

    public function getLikesCountAttribute() {
        return $this->likes->count();
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    } 

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function userHasLiked(User $user) {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
