<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Message extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia;
    protected $fillable=['content','sender_id','receiver_id','chat_id'];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaCollection('chatFiles');
    }
    public function sender()
    {
        return $this->belongsTo(User::class,'sender_id');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class,'receiver_id');
    }
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
