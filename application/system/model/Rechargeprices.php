<?php
/* |------------------------------------------------------
 * | 书币与会员价格管理模型
 * |------------------------------------------------------
 * | 添加
 * | 修改
 * */
namespace app\system\model;
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
        return $status[$value];
    }
    public function add(){
        $this->data = input();
        if($this->save()){
            return true;
        }else{
            return false;
        }
    }
    public function updata(){
        $id = input('id');
        $rs = $this->save($_POST, ['id' => $id]);
        if($rs){
            return true;
        }else{
            return false;
        }
    }
}