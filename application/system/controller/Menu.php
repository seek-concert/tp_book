<?php
/* |------------------------------------------------------
 * | 功能与菜单
 * |------------------------------------------------------
 * | 初始化操作
 * | 列表
 * | 列表全部
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
use think\Db;

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
        $menus=Menus::field(['id','parent_id','name','icon','sort','url','display','status'])->order('sort asc')->select();
        $table_menus='';
        if($menus){
            $array=[];
            foreach ($menus as $menu){
                $menu->display=$menu->show($menu->display);
                $menu->status=$menu->status($menu->status);
                $menu->add_url=url('add',['id'=>$menu->id]);
                $menu->detail_url=url('detail',['id'=>$menu->id]);
                $menu->delete_url=url('delete',['ids'=>$menu->id]);
                $array[]=$menu;
            }
            $str = "
                    <tr data-tt-id='\$id' data-tt-parent-id='\$parent_id' >
                        <td>
                            <input class='va_m' type='checkbox' name='ids[]' value='\$id' onclick='checkBoxOp(this)' id='check-\$id'/>
                        </td>
                        <td><input style='width: 50px;' type='text' name='sorts[\$id]' value='\$sort' id='input-\$id' data-id='\$id'></td>
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

    /* ========== 列表全部 ========== */
    public function all(){
        /* ********** 查询条件 ********** */
        $datas=[];
        $where=[];
        $field=['id','name','parent_id','icon','url','display','status','sort','deleted_at'];
        /* ++++++++++ 菜单名称 ++++++++++ */
        $name=trim(request()->param('name'));
        if($name){
            $where['name']=['like','%'.$name.'%'];
            $datas['name']=$name;
        }
        /* ++++++++++ 路由地址 ++++++++++ */
        $url=trim(request()->param('url'));
        if($url){
            $where['url']=['like','%'.$url.'%'];
            $datas['url']=$url;
        }
        /* ++++++++++ 显示状态 ++++++++++ */
        $display=request()->param('display');
        if(is_numeric($display) && in_array($display,[0,1])){
            $where['display']=$display;
            $datas['display']=$display;
        }
        /* ++++++++++ 使用状态 ++++++++++ */
        $status=request()->param('status');
        if(is_numeric($status) && in_array($status,[0,1])){
            $where['status']=$status;
            $datas['status']=$status;
        }
        /* ++++++++++ 排序 ++++++++++ */
        $ordername=request()->param('ordername');
        $ordername=$ordername?$ordername:'sort';
        $datas['ordername']=$ordername;
        $orderby=request()->param('orderby');
        $orderby=$orderby?$orderby:'asc';
        $datas['orderby']=$orderby;
        /* ++++++++++ 每页条数 ++++++++++ */
        $nums=[config('paginate.list_rows'),30,50,100,200,500];
        sort($nums);
        $datas['nums']=$nums;
        $display_num=request()->param('display_num');
        $display_num=$display_num?$display_num:config('paginate.list_rows');
        $datas['display_num']=$display_num;
        /* ++++++++++ 是否删除 ++++++++++ */
        $deleted=request()->param('deleted');
        if(is_numeric($deleted) && in_array($deleted,[0,1])){
            $datas['deleted']=$deleted;
            if($deleted==1){
                $menus=Menus::onlyTrashed()->where($where)->field($field)->order([$ordername=>$orderby])->paginate($display_num);
            }else{
                $menus=Menus::where($where)->field($field)->order([$ordername=>$orderby])->paginate($display_num);
            }
        }else{
            $menus=Menus::withTrashed()->where($where)->field($field)->order([$ordername=>$orderby])->paginate($display_num);
        }

        $datas['menus']=$menus;

        $this->assign($datas);

        return view();
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
            return $this->success('修改成功','');
        }else{
            return $this->error('修改失败');
        }
    }

    /* ========== 排序 ========== */
    public function sort(){
        $inputs=input();
        $ids=isset($inputs['ids'])?$inputs['ids']:'';
        $sorts=$inputs['sorts'];
        if(empty($ids)){
            return $this->error('至少选择一项');
        }
        $datas=[];
        $i=0;
        $time=time();
        foreach ($ids as $id){
            $datas[$i]['id']=$id;
            $datas[$i]['sort']=(int)$sorts[$id];
            $datas[$i]['updated_at']=$time;
            $i++;
        }
        $sqls=batch_update_sql('menu',['id','sort','updated_at'],$datas,['sort','updated_at'],'id');
        $res=false;
        if($sqls){
            foreach ($sqls as $sql){
                $res=db()->execute($sql);
            }
        }
        if($res){
            return $this->success('修改成功','');
        }else{
            return $this->error('修改失败');
        }
    }

    /* ========== 显示状态 ========== */
    public function show(){
        $inputs=input();
        $ids=isset($inputs['ids'])?$inputs['ids']:'';
        $display=$inputs['display'];

        if(empty($ids)){
            return $this->error('至少选择一项');
        }
        if(!in_array($display,[0,1])){
            return $this->error('错误操作');
        }
        $res=Menus::withTrashed()->whereIn('id',$ids)->update(['display'=>$display,'updated_at'=>time()]);
        if($res){
            return $this->success('修改成功','');
        }else{
            return $this->error('修改失败');
        }
    }

    /* ========== 状态 ========== */
    public function status(){
        $inputs=input();
        $ids=isset($inputs['ids'])?$inputs['ids']:'';
        $status=$inputs['status'];

        if(empty($ids)){
            return $this->error('至少选择一项');
        }
        if(!in_array($status,[0,1])){
            return $this->error('错误操作');
        }
        $res=Menus::withTrashed()->whereIn('id',$ids)->update(['status'=>$status,'updated_at'=>time()]);
        if($res){
            return $this->success('修改成功','');
        }else{
            return $this->error('修改失败');
        }
    }

    /* ========== 删除 ========== */
    public function delete(){
        $inputs=input();
        $ids=isset($inputs['ids'])?$inputs['ids']:'';

        if(empty($ids)){
            return $this->error('至少选择一项');
        }
        if(is_array($ids)){
            $fail_ids=[];
            $del_ids[]='0';
            foreach ($ids as $id){
                $count=Menus::field('id')->where('parent_id',$id)->count();
                if($count){
                    $fail_ids[]=$id;
                }else{
                    $del_ids[]=$id;
                }
            }
            $res=Menus::destroy($del_ids);
            $fail_num=count($fail_ids);
            if($res){
                if($fail_num){
                    return $this->success('部分删除成功！部分存在子菜单，请先删除其全部子菜单后重试！','');
                }else{
                    return $this->success('删除成功','');
                }
            }else{
                return $this->error('删除失败');
            }
        }else{
            $count=Menus::field('id')->where('parent_id',$ids)->count();
            if($count){
                return $this->error('其下存在子菜单，请先删除全部子菜单后重试！');
            }
            $res=Menus::destroy($ids);
            if($res){
                return $this->success('删除成功','');
            }else{
                return $this->error('删除失败');
            }
        }
    }

    /* ========== 恢复 ========== */
    public function restore(){
        $inputs=input();
        $ids=isset($inputs['ids'])?$inputs['ids']:'';

        if(empty($ids)){
            return $this->error('至少选择一项');
        }

        $res=Db::table('menu')->whereIn('id',$ids)->where('`deleted_at` is not null')->update(['deleted_at'=>null,'updated_at'=>time()]);
        if($res){
            return $this->success('恢复成功','');
        }else{
            return $this->error('恢复失败');
        }
    }

    /* ========== 销毁 ========== */
    public function destroy(){
        $inputs=input();
        $ids=isset($inputs['ids'])?$inputs['ids']:'';

        if(empty($ids)){
            return $this->error('至少选择一项');
        }
        $res=Menus::onlyTrashed()->whereIn('id',$ids)->delete(true);
        if($res){
            return $this->success('销毁成功','');
        }else{
            return $this->error('销毁失败');
        }
    }
}
