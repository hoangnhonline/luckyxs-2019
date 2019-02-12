<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Staff extends Model  {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'staff'; 

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
        'short_name',         
        'full_name', 
        'phone', 
        'facebook_url', 
        'address', 
        'date_join',
        'date_off', 
        'status',
        'family_phone',
        'family_name',
        'type',
        'type_1',
        'type_2',    
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
    public static function getListStaff($type){
        $staffList = Staff::where('status', 1)->where('type', $type)->orWhere('type_1', $type)->orWhere('type_2', $type)->orderBy('id', 'DESC')->get();
        return $staffList;
    }
}
