<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class OrderDetailHistory extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'order_detail_history';	

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
                    'ngay_giao', 
                    'order_detail_id',
                    'status', 
                    'created_user',
                    'updated_user'
                ];
    
}
