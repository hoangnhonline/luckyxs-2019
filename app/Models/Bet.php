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
        'len',
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
        'str_channel',
        'str_number',
        'bet_day',
        'status',
        'created_at',
        'updated_at',
    ];
    public function betType()
    {
        return $this->belongsTo('App\Models\BetType', 'bet_type_id');
    }
    public function channel()
    {
        return $this->belongsTo('App\Models\Channel', 'channel_id');
    }
    public function calTotal($id)
    {
        return Bet::where('id', $id)->orWhere('refer_bet_id', $id)->sum('total');
    } 
    public function calTotal2So($id)
    {
        return Bet::where(function($query) use ($id){
            $query->where('id', $id);
            $query->orWhere('refer_bet_id', $id);
        })->where('len', 2)->sum('total');
    } 
    public function calTotal3So($id)
    {
        return Bet::where(function($query) use ($id){
            $query->where('id', $id);
            $query->orWhere('refer_bet_id', $id);
        })->where('len', '>', 2)->sum('total');
    } 
}
