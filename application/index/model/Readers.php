<?php
/* |------------------------------------------------------
 * | 读者 模型
 * |------------------------------------------------------
 * */
namespace app\index\model;

use think\Model;

class Readers extends Model
{

    protected $table='reader';
    protected $pk='id';
    protected $createTime='created_at';
    protected $updateTime='updated_at';
    protected $autoWriteTimestamp = true;
    protected $field=true;

    public function getLoginAtAttr($value){
        return date('Y-m-d H:i:s',$value);
    }

    public function login_data(){
        $data['login_at']=time();
        $data['login_ip']=request()->ip();
        return $data;
    }
}