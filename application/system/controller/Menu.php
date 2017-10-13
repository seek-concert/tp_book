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
        $menus=Menus::field(['id','parent_id','name','icon','sort','url','display','status'])->where('status',1)->order('sort asc')->select();
        $table_menus='';
        if($menus){
            $array=[];
            foreach ($menus as $menu){
                $menu->display=$menu->show($menu->display);
                $menu->status=$menu->status($menu->status);
                $menu->add_url=url('add',['id'=>$menu->id]);
                $menu->detail_url=url('detail',['id'=>$menu->id]);
                $menu->delete_url=url('delete',['id'=>$menu->id]);
                $array[]=$menu;
            }
            $str = "
                    <tr data-tt-id='\$id' data-tt-parent-id='\$parent_id' >
                        <td>
                            <input class='va_m' type='checkbox' name='ids[]' value='\$id' onclick='checkBoxOp(this)' id='check-\$id'/>
                        </td>
                        <td><input style='width: 50px;' type='text' name='sort[\$id]' value='\$sort' id='input-\$id' data-id='\$id'></td>
                        <td>\$id</td>
                        <td>\$space \$icon \$name</td>
                        <td>\$url</td>
                        <td>\$display | \$status</td>
                        <td>
                            <button type='button' class='btn' onclick='layerIfWindow(&apos;添加菜单&apos;,&apos;\$add_url&apos;,&apos;&apos;,&apos;335&apos;)' >添加子菜单</button>
                            <button type='button' class='btn' onclick='layerIfWindow(&apos;菜单信息&apos;,	&apos;\$detail_url&apos;,&apos;&apos;,&apos;400&apos;)' >菜单信息</button>
                            <button type='button' data-action='\$delete_url' class='btn js-ajax-form-btn' data-notice='确定要删除吗？'>删除</button>
                        </td>
                    </tr>
                    ";
            $table_menus=get_tree($array,$str,0,1,['&nbsp;&nbsp;┃┅','&nbsp;&nbsp;┣┅','&nbsp;&nbsp;┗┅'],'&nbsp;&nbsp;');
        }
        return view('index',['table_menus'=>$table_menus]);
    }

    /* ========== 添加 ========== */
    public function add($id=0){
        if(request()->isPost()){
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

            $result=$this->validate(input(),$rules,$msg);
            if(true !== $result){
                $this->error($result);
            }

            $menu_model=new Menus();
            $other_datas=$menu_model->other_data(input());
            $datas=array_merge(input(),$other_datas);
            $menu_model->save($datas);
            if($menu_model !== false){
                return $this->success('保存成功','');
            }else{
                return $this->error('保存失败');
            }
        }else{
            $menus=Menus::field(['id','parent_id','name','sort','status'])->where('status',1)->select();
            $options_menus='';
            if($menus){
                $array=[];
                foreach ($menus as $menu){
                    $menu->selected=$menu->id==$id?'selected':'';
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
    public function detail($id=null){
        if(!$id){
            return $this->error('至少选择一项');
        }
        $infos=Menus::withTrashed()->find($id);
        if(!$infos){
            return $this->error('选择项目不存在');
        }

        $menus=Menus::field(['id','parent_id','name','sort','status'])->where('status',1)->select();
        $options_menus='';
        if($menus){
            $array=[];
            foreach ($menus as $menu){
                $menu->selected=$menu->id==$infos->parent_id?'selected':'';
                $array[]=$menu;
            }
            $options_menus=get_tree($array);
        }
        return view('modify',[
            'infos'=>$infos,
            'options_menus'=>$options_menus,
        ]);
    }

    /* ========== 修改 ========== */
    public function edit(){
        $id=input('id');
        if(!$id){
            return $this->error('错误操作');
        }
        $datas=input();
        $rules=[
            'name'=>'require|unique:menu,name,'.$id.',id',
            'url'=>'require|unique:menu,url,'.$id.',id',
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

        $menu_model=new Menus();
        $other_datas=$menu_model->other_data(input());
        $datas=array_merge(input(),$other_datas);
        $menu_model->isUpdate(true)->save($datas);
        if($menu_model !== false){
            return $this->success('保存成功','');
        }else{
            return $this->error('保存失败');
        }
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
