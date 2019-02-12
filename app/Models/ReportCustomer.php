<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ReportCustomer extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'report_customer';	

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
    protected $fillable = ['customer_id', 'total_order', 'total_product', 'status'];
        
}
