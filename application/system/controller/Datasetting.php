<?php
/* |------------------------------------------------------
 * | 数据设置管理
 * |------------------------------------------------------
 * */
namespace app\system\controller;
use app\system\model\Datasettings;

class Datasetting extends Auth
{
    /* ========== 数据设置 ========== */
    public function index(){
        $model=new Datasettings();
        $info=$model->find();
        $this->assign('info',$info);
        if(request()->isPost()){
            $rs = $model->updata();
            if($rs !== false){
                $this->success('操作成功','');
            }else{
                $this->error('操作失败','');
            }
        }else{
            return view();
        }
    }
}