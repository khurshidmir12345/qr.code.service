<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrCode extends Model
{
    use HasFactory;

    protected $table = 'qr_codes';
    protected $fillable = ['name', 'qr_link', 'qr_image', 'generated_link', 'views'];




    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
