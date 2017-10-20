<?php
/* |------------------------------------------------------
 * | 充值订单管理模型
 * |------------------------------------------------------
 * */
namespace app\system\model;
use think\Model;

class Rechargeorderss extends Model
{
    protected $table = 'recharge_orders';
    protected $type = [
        'created_at'  =>  'timestamp',
        'finished_at'  =>  'timestamp'
    ];

    public function getTypeAttr($value=null)
    {
        $status = [0=>'书币',1=>'会员'];
        if(is_numeric($value) && in_array($value,[0,1])){
            return $status[$value];
        }else{
            return $status;
        }

    }
    /*----- 关联读者 -----*/
    public function Readers()
    {
        return $this->belongsTo('Readers','reader_id');
    }

}