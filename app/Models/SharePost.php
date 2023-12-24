<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharePost extends Model
{
    use HasFactory;
    protected $fillable=['title', 'share_by','post_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function shareBy()
    {
       return $this->belongsTo(User::class,'share_by');
    }
}
