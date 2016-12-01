<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Customer extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     * 淘车乐客户表
     * 定义模型对应数据表及主键
     * @var string
     */
    protected $table      = 'tcl_customer';
    protected $primaryKey ='id';

    /**
     * The attributes that are mass assignable.
     * 定义可批量赋值字段
     * @var array
     */
    protected $fillable = ['name', 'password', 'telephone',  'qq_number', 'indentily_card', 'wx_number', 'address', 'creater_id','status', 'user_img', 'sex', 'type', 'customer_res'];

    /**
     * The attributes excluded from the model's JSON form.
     * //在模型数组或 JSON 显示中隐藏某些属性
     * @var array
     */
    protected $hidden = [   
        'password', 'remember_token',
    ];

    // 定义User表与Customer表一对多关系
    public function belongsToUser(){

      return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
