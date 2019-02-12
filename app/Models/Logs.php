<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Logs extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'logs';

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
                    'user_id', 
                    'action', 
                    'type',
                    'object_id',
                    'old_data',
                    'new_data',
                ];
    
    public function user()
    {
        return $this->belongsTo('App\Models\Account', 'user_id');
    }
    public function staff()
    {
        return $this->belongsTo('App\Models\Staff', 'object_id');
    }
     public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'object_id');
    }    
    public function orders()
    {
        return $this->belongsTo('App\Models\Orders', 'object_id');
    }
    public function orderDetail()
    {
        return $this->belongsTo('App\Models\OrderDetail', 'object_id');
    }
}
