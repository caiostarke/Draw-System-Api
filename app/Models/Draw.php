<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Draw extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "name",
        "image",
        "body_content"
    ];


    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    } 


}
