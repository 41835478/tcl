<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Want extends Model
{
    /**
     * The database table used by the model.
     * 定义模型对应数据表及主键
     * @var string
     */
    // protected $table = 'users';
    protected $table = 'tcl_want';
    protected $primaryKey ='id';

    /**
     * The attributes that are mass assignable.
     * 定义可批量赋值字段
     * @var array
     */
    protected $fillable = ['want_code', 'name', 'car_type', 'brand_id', 'categorey_id', 'car_factory', 'cate_id', 'capacity', 'gearbox', 'bottom_price', 'top_price', 'age', 'mileage', 'out_color', 'inside_color', 'customer_id', 'creater_id', 'want_eara', 'remark', 'want_status', 'shop_id', 'is_top', 'recommend'];

    /**
     * The attributes excluded from the model's JSON form.
     * //在模型数组或 JSON 显示中隐藏某些属性
     * @var array
     */
    protected $hidden = [];

    // 搜索条件处理
    public function addCondition($requestData, $is_self){

        $query = $this;
        // dd($query);
        if($is_self){

            if(!(Auth::user()->isSuperAdmin())){

               if(Auth::user()->isMdLeader()){
                    //店长
                    $user_shop_id = Auth::user()->belongsToShop->id; //用户所属门店id
        
                    // $this->where('shop_id', $user_shop_id);
               $query = $query->where('shop_id', '6');    
                }else{
                    //店员
                    // $this->where('creater_id', Auth::id());
                    $query = $query->where('creater_id', '3');  
                } 
            }           
        }

        if(isset($requestData['want_status']) && $requestData['want_status'] != ''){

            $query = $query->where('want_status', $requestData['want_status']);
        }else{

            $query = $query->where('want_status', '1');
        }

        if(!empty($requestData['want_code'])){

            $query = $query->where('want_code', $requestData['want_code']);
        }

        return $query;
    }

    /**
     * 推荐车型信息的查询作用域
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOsRecommend($query, $requestData)
    {
        if(isset($requestData['top_price'])){
            $query = $query->where('top_price',    '<=', $requestData['top_price']);
        }
        
        if(isset($requestData['bottom_price'])){
            $query = $query->where('bottom_price',    '<=', $requestData['bottom_price']);
        }
        
        $query = $query->where('car_status', '1');
        return $query;
    }

    public function justSelfSelect($query){

        /*if(Auth::user()->isSuperAdmin()){
            // 超级管理员
            return $query;
        }*/
        if(Auth::user()->isMdLeader()){
            //店长
            $user_shop_id = Auth::user()->belongsToShop->id; //用户所属门店id

            // $this->where('shop_id', $user_shop_id);
            $query = $this->where('shop_id', '6');

        }else{
            //店员
            // $this->where('creater_id', Auth::id());
            $query = $this->where('creater_id', '3');

        }

        return $query;
    }

    // 定义Category表与Want(客源）表一对多关系
    public function belongsToCategory(){

      return $this->belongsTo('App\Category', 'cate_id', 'id')->select('id', 'name AS category_name');
    }

    // 定义Shop表与Want(客源）表一对多关系
    public function belongsToShop(){

      return $this->belongsTo('App\Shop', 'shop_id', 'id')->select('id', 'name AS shop_name');
    }

    // 定义User表与Want(客源）表一对多关系
    public function belongsToUser(){

      return $this->belongsTo('App\User', 'creater_id', 'id')->select('id', 'nick_name', 'telephone as creater_telephone');
    }

    // 定义want表与want_follow表一对多关系
    public function hasManyFollow()
    {
        return $this->hasMany('App\WantFollow', 'want_id', 'id');
    }

    // 定义want表与customer表一对多关系
    public function belongsToCustomer(){

      return $this->belongsTo('App\Customer', 'customer_id', 'id')->select('id', 'name as customer_name', 'telephone as customer_telephone');
    }

    // 定义Want表与chance表一对多关系
    public function hasManyChances()
    {
        return $this->hasMany('App\Chance', 'want_id', 'id');
    }
}
