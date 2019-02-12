<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Bet extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'bet';

	 /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bet_type_id', 
        'channel_id',
        'message_id',
        'refer_bet_id',
        'number_1',
        'number_2',        
        'price',
        'total',
        'after',
        'result',
        'win',
        'is_main',
        'bet_day',
        'status',
        'created_at',
        'updated_at',
    ];
        
}
