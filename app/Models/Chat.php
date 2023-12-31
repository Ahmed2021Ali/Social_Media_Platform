<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use function Laravel\Prompts\password;

class Chat extends Model
{
    use HasFactory ;
    protected $fillable=['sender_id','receiver_id'];

    public function sender()
    {
        return $this->belongsTo(User::class,'sender_id');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class,'receiver_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
