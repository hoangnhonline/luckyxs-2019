<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Customer extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'customer';

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
        'name', 
        'address', 
        'phone', 
        'email', 
        'information', 
        'vong_nguc', 
        'vong_eo', 
        'vong_mong', 
        'ha_nguc', 
        'ha_eo',
        'rong_vai', 
        'dai_tay_ao',
        'dai_ao',
        'vong_co',
        'vong_nach',
        'bap_tay_tren',
        'bap_tay_duoi',
        'vong_day',
        'vong_nguc_tren',
        'vong_nguc_duoi',
        'dai_quan',
        'them_1',
        'them_2',
        'them_3',
        'them_4',
        'dai_raglan',        
        'status',                             
        'created_user', 
        'updated_user'
    ];    
    public function createdUser()
    {
        return $this->belongsTo('App\Models\Account', 'created_user');
    }
    public function updatedUser()
    {
        return $this->belongsTo('App\Models\Account', 'updated_user');
    }    
    public function orders()
    {
        return $this->hasMany('App\Models\Orders', 'customer_id')->where('status', 1);
    }
}
