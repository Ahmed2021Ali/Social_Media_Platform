<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;
    protected $fillable=['title','description','file','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function interactions()
    {
       return $this->hasMany(Interaction::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaCollection('postsFiles');
    }
}
