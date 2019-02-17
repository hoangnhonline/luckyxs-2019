<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageMau extends Model
{
    protected $table = 'message_mau';

    protected $fillable = [
        'tel_id', 'content'
    ];
}
