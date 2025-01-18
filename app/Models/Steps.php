<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Steps extends Model
{
    use HasFactory;

    protected $table = 'user_steps';
    
    protected $fillable = ['chat_id', 'step', 'name', 'link'];
}
