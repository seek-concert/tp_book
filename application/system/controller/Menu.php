<?php
/* |------------------------------------------------------
 * | 功能与菜单
 * |------------------------------------------------------
 * | 初始化操作
 * | 列表
 * | 添加
 * | 详情
 * | 修改
 * | 排序
 * | 显示状态
 * | 状态
 * | 删除
 * | 恢复
 * | 销毁
 * */
namespace app\system\controller;

use \app\system\model\Menus;

class Menu extends Auth
{
    /* ========== 初始化 ========== */
    public function _initialize()
    {
        parent::_initialize();

    }

    /* ========== 列表 ========== */
    public function index()
    {
        return view();
    }

    /* ========== 添加 ========== */
    public function add(){
        if(request()->isPost()){
            $datas=input();
            $rules=[
                'name'=>'require|unique:menu',
                'url'=>'require|unique:menu',
            ];
            $msg=[
                'name.require'=>'名称不能为空',
                'name.unique'=>'名称已存在',
                'url.require'=>'路由地址不能为空',
                'url.unique'=>'路由地址已存在',
            ];

            $result=$this->validate($datas,$rules,$msg);
            if(true !== $result){
                $this->error($result);
            }

            $menu_model=new Menus($datas);
            $menu_model->other_data($datas);
            $menu_model->save();
            if($menu_model !== false){
                $this->success('保存成功');
            }else{
                $this->error('保存失败');
            }
        }else{
            $menus=Menus::field(['id','parent_id','name','sort','status'])->where('status',1)->select();
            $options_menus='';
            if($menus){
                $array=[];
                foreach ($menus as $menu){
                    $menu->selected=$menu->id==input('id')?'selected':'';
                    $array[]=$menu;
                }
                $options_menus=get_tree($array);
            }

            return view('modify',[
                'options_menus'=>$options_menus
            ]);
        }
    }

    /* ========== 详情 ========== */
    public function detail(){

    }

    /* ========== 修改 ========== */
    public function edit(){

    }

    /* ========== 排序 ========== */
    public function sort(){

    }

    /* ========== 显示状态 ========== */
    public function show(){

    }

    /* ========== 状态 ========== */
    public function status(){

    }

    /* ========== 删除 ========== */
    public function delete(){

    }

    /* ========== 恢复 ========== */
    public function restore(){

    }

    /* ========== 销毁 ========== */
    public function destory(){

    }
}
