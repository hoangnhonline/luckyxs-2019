<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Account extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

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
    protected $fillable = ['full_name', 'email', 'password', 'status', 'changed_password', 'remember_token', 'role', 'leader_id', 'created_user', 'updated_user', 'display_name'];
    
    public function orders()
    {
        return $this->hasMany('App\Models\Orders', 'created_user');
    }
    public function customers()
    {
        return $this->hasMany('App\Models\Customer', 'created_user');
    }
}