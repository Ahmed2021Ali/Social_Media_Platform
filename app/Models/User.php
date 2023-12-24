<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable ,InteractsWithMedia;


    protected $fillable = [
        'name',
        'email',
        'password',
        'provider',
        'provider_id',
        'bio',
        'birth',
        'phone',
        'file',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaCollection('usersImages');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function interactions()
    {
        return $this->hasMany(Interaction::class);
    }
    public function requests()
    {
        return $this->hasMany(FriendRequest::class,'receiver_id');
    }

    public function sharePosts()
    {
        return $this->hasMany(SharePost::class,'share_by');
    }

}
