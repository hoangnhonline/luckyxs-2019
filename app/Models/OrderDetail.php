<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class OrderDetail extends Model  {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_detail';

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
        'order_id', 
        'loai_id', 
        'product_code', 
        'number_product', 
        'price', 
        'total_price',  
        'mau_id', 
        'size_id', 
        'kieu_id', 
        'image_url', 
        'status',  
        'ngay_giao', 
        'form_status_id',
        'form_ok_id', 
        'staff_rap', 
        'staff_cat',  
        'staff_may', 
        'staff_ren',
        'staff_cuom', 
        'nguoi_nhan',  
        'process', 
        'notes',
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
        return $this->belongsTo('App\Models\Orders', 'order_id');
    }
    public function formStatus()
    {
        return $this->belongsTo('App\Models\FormStatus', 'form_status_id');
    }
    public function loai()
    {
        return $this->belongsTo('App\Models\Loai', 'loai_id');
    }
    public function formOk()
    {
        return $this->belongsTo('App\Models\FormOk', 'form_ok_id');
    }
    public function mau()
    {
        return $this->belongsTo('App\Models\Mau', 'mau_id');
    }
    public function size()
    {
        return $this->belongsTo('App\Models\Size', 'size_id');
    }
    public function kieu()
    {
        return $this->belongsTo('App\Models\Kieu', 'kieu_id');
    }
    public function nhan()
    {        
        return $this->belongsTo('App\Models\Staff', 'nguoi_nhan');
    }
    public function rap()
    {        
        return $this->belongsTo('App\Models\Staff', 'staff_rap');
    }
    public function cat()
    {        
        return $this->belongsTo('App\Models\Staff', 'staff_cat');
    }
    public function may()
    {        
        return $this->belongsTo('App\Models\Staff', 'staff_may');
    }
    public function ren()
    {        
        return $this->belongsTo('App\Models\Staff', 'staff_ren');
    }
    public function cuom()
    {        
        return $this->belongsTo('App\Models\Staff', 'staff_cuom');
    }
    public static function deliveryDate($order_detail_id)
    {        
        return OrderDetailHistory::where('order_detail_id',$order_detail_id)->orderBy('ngay_giao')->get();
    }

}
