<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KnowledgeBase extends Model
{
    use HasFactory;
    
    protected $fillable = ['title', 'content'];

    public function chats()
    {
        return $this->belongsToMany(Chat::class);
    }
}