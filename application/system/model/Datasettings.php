<?php
/* |------------------------------------------------------
 * | 数据设置管理模型
 * |------------------------------------------------------
 * */
namespace app\system\model;
use think\Model;

class Datasettings extends Model
{
    protected $table='data_setting';
    protected $updateTime='updated_at';
    protected $autoWriteTimestamp = true;
    protected $field=true;
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