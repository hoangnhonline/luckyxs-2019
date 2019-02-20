<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Channel extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'channel';

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
    protected $fillable = ['code', 'name', 'display_order'];

    public static function getChannelName($channelArr)
    {
        $count = 0;
        $str = "";
        foreach($channelArr as $channelId){
            $str.=Channel::find($channelId)->code;
            if($count==0 && count($channelArr)==2){
                $str.="-";
            }
            $count++;
        }

        return strtoupper($str);
    }
}