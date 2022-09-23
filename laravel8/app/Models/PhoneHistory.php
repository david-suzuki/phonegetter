<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneHistory extends Model
{
    use HasFactory;

    public function phone()
    {
        return $this->belongsTo(Phone::class, 'phone_id', 'id');
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
