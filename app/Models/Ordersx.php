<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Ordersx extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'ordersx';

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
        'customer_id', 
        'total_product', 
        'total_price', 
        'method_id', 
        'payment_id', 
        'ngay_dat',  
        'ngay_giao', 
        'ngay_giao_max', 
        'description', 
        'order_status', 
        'status',  
        'da_soan', 
        'call_status',                                                                               
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
    public function orderDetails()
    {
        return $this->hasMany('App\Models\OrderDetail', 'order_id');
    }
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
    public static function getList($paramArr, $type = 1, $status = 1){        
        $query = self::where('status', $status);
        if( $paramArr['order_status']){
            $query->where('order_status', $paramArr['order_status']);
        }
        if( $paramArr['da_soan'] ){
            $query->where('da_soan', $paramArr['da_soan']);
        }
        if( $paramArr['customer_id'] ){
            $query->where('customer_id',$paramArr['customer_id']);
        }
        if($type == 1){
            if( $paramArr['from_date'] ){
                $from_date = date('Y-m-d', strtotime($paramArr['from_date']));
                $query->where('ngay_giao', '>=', $from_date);
            }
            if( $paramArr['to_date'] ){
                $to_date = date('Y-m-d', strtotime($paramArr['to_date']));
                $query->where('ngay_giao', '<=', $to_date);
            }
        }else{
            if( $paramArr['from_date'] ){
                $from_date = date('Y-m-d', strtotime($paramArr['from_date']));
                $query->where('ngay_giao_max', '>=', $from_date);
            }
            if( $paramArr['to_date'] ){
                $to_date = date('Y-m-d', strtotime($paramArr['to_date']));
                $query->where('ngay_giao_max', '<=', $to_date);
            }
        }
        $query->orderBy('ngay_giao', 'asc');
        if(isset($paramArr['limit'])){
            return $query->limit($paramArr['limit'])->get();
        }  
        //dd($query->toSql());      
        return $query->paginate(config('sites.pagination'));
        
    }
    public function method()
    {
        return $this->belongsTo('App\Models\Method', 'method_id');
    }
    public function payment()
    {
        return $this->belongsTo('App\Models\Payment', 'payment_id');
    }
    public function getProcessDetailOrder($orderId){
        $arr = array();        
        $dataList = DB::table('order_detail')
                 ->select('process', DB::raw('count(id) as total'))
                 ->where('order_id', $orderId)
                 ->groupBy('process')
                 ->get();
        foreach($dataList as $data){
            if($data['total'] > 0){
                $arr[$data['process']] = $data['total'];    
            }
        }    
        return $arr;
    }    
}
