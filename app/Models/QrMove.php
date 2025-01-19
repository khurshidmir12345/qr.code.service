<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrMove extends Model
{
    use HasFactory;

    protected $table = 'qr_moves';

    protected $fillable = [
        'chat_id',
        'qr_id',
    ];
}
