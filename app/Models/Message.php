<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'tel_id', 'content'
    ];

    public function calTotal2So($id)
    {
        return Bet::where('message_id', $id)->where('len', 2)->sum('total');
    }
    public function calTotal3So($id)
    {
        return Bet::where('message_id', $id)->where('len','>', 2)->sum('total');
    }
}
