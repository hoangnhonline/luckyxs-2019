<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class FormStatus extends Model  {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'form_status';

     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['value', 'color_code', 'status'];
        
}
