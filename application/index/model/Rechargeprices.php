<?php
/* |------------------------------------------------------
 * | 书币与会员价格管理模型
 * |------------------------------------------------------
 * | 添加
 * | 修改
 * */
namespace app\index\model;
use think\Model;
use traits\model\SoftDelete;

class Rechargeprices extends Model
{
    use SoftDelete;
    protected $table = 'recharge_price';
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $deleteTime='deleted_at';
    protected $autoWriteTimestamp = true;
    protected $field = true;

    public function getTypeAttr($value)
    {
        $status = [0=>'书币',1=>'会员'];
        if(is_numeric($value) && in_array($value,[0,1])){
            return $status[$value];
        }else{
            return $status;
        }
    }

}