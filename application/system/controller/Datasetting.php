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

        if(request()->isPost()){
            if(input('id')){
                $model->save(input(),['id'=>input('id')]);
            }else{
                $model->save(input());
            }
            if($model !== false){
                $this->assign('notice',"<script>layer.msg('操作成功',function() {});</script>");
            }else{
                $this->assign('notice',"<script>layer.msg('操作失败',function() {});</script>");
            }
        }

        $info=$model->find();
        $this->assign('info',$info);

        return view();
    }
}