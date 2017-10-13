<?php
/* |------------------------------------------------------
 * | 权限与角色
 * |------------------------------------------------------
 * | 初始化操作
 * | 列表
 * | 列表全部
 * | 添加
 * | 详情
 * | 修改
 * | 排序
 * | 状态
 * | 删除
 * | 恢复
 * | 销毁
 * */
namespace app\system\controller;

use \app\system\model\Roles;
use think\Db;

class Role extends Auth
{
    /* ========== 初始化 ========== */
    public function _initialize()
    {
        parent::_initialize();

    }

    /* ========== 列表 ========== */
    public function index()
    {
        $roles=Roles::field(['id','parent_id','name','status'])->order('sort asc')->select();
        $table_roles='';
        if($roles){
            $array=[];
            foreach ($roles as $role){
                $role->status=$role->status($role->status);
                $role->add_url=url('add',['id'=>$role->id]);
                $role->detail_url=url('detail',['id'=>$role->id]);
                $role->delete_url=url('delete',['ids'=>$role->id]);
                $array[]=$role;
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
                            <button type='button' class='btn' onclick='layerIfWindow(&apos;添加角色&apos;,&apos;\$add_url&apos;,&apos;&apos;,&apos;335&apos;)' >添加下级</button>
                            <button type='button' class='btn' onclick='layerIfWindow(&apos;角色信息&apos;,	&apos;\$detail_url&apos;,&apos;&apos;,&apos;400&apos;)' >角色信息</button>
                            <button type='button' data-action='\$delete_url' class='btn js-ajax-form-btn' data-notice='确定要删除吗？'>删除</button>
                        </td>
                    </tr>
                    ";
            $table_roles=get_tree($array,$str,0,1,['&nbsp;&nbsp;┃┅','&nbsp;&nbsp;┣┅','&nbsp;&nbsp;┗┅'],'&nbsp;&nbsp;');
        }
        return view('index',['table_roles'=>$table_roles]);
    }

    /* ========== 列表全部 ========== */
    public function all(){
        /* ********** 查询条件 ********** */
        $datas=[];
        $where=[];
        $field=['id','name','parent_id','icon','url','display','status','sort','deleted_at'];
        /* ++++++++++ 角色名称 ++++++++++ */
        $name=trim(request()->param('name'));
        if($name){
            $where['name']=['like','%'.$name.'%'];
            $datas['name']=$name;
        }
        /* ++++++++++ 状态 ++++++++++ */
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
                $roles=Roles::onlyTrashed()->where($where)->field($field)->order([$ordername=>$orderby])->paginate($display_num);
            }else{
                $roles=Roles::where($where)->field($field)->order([$ordername=>$orderby])->paginate($display_num);
            }
        }else{
            $roles=Roles::withTrashed()->where($where)->field($field)->order([$ordername=>$orderby])->paginate($display_num);
        }

        $datas['roles']=$roles;

        $this->assign($datas);

        return view();
    }

    /* ========== 添加 ========== */
    public function add($id=0){
        if(request()->isPost()){
            $rules=[
                'name'=>'require|unique:role',
                'url'=>'require|unique:role',
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

            $role_model=new Roles();
            $other_datas=$role_model->other_data(input());
            $datas=array_merge(input(),$other_datas);
            $role_model->save($datas);
            if($role_model !== false){
                return $this->success('保存成功','');
            }else{
                return $this->error('保存失败');
            }
        }else{
            $roles=Roles::field(['id','parent_id','name','sort','status'])->where('status',1)->select();
            $options_roles='';
            if($roles){
                $array=[];
                foreach ($roles as $role){
                    $role->selected=$role->id==$id?'selected':'';
                    $array[]=$role;
                }
                $options_roles=get_tree($array);
            }

            return view('modify',[
                'options_roles'=>$options_roles
            ]);
        }
    }

    /* ========== 详情 ========== */
    public function detail($id=null){
        if(!$id){
            return $this->error('至少选择一项');
        }
        $infos=Roles::withTrashed()->find($id);
        if(!$infos){
            return $this->error('选择项目不存在');
        }

        $roles=Roles::field(['id','parent_id','name','sort','status'])->where('status',1)->select();
        $options_roles='';
        if($roles){
            $array=[];
            foreach ($roles as $role){
                $role->selected=$role->id==$infos->parent_id?'selected':'';
                $array[]=$role;
            }
            $options_roles=get_tree($array);
        }
        return view('modify',[
            'infos'=>$infos,
            'options_roles'=>$options_roles,
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
            'parent_id'=>'notIn:'.$id,
            'name'=>'require|unique:role,name,'.$id.',id',
            'url'=>'require|unique:role,url,'.$id.',id',
        ];
        $msg=[
            'parent_id.notIn'=>'上级角色不能为本身',
            'name.require'=>'名称不能为空',
            'name.unique'=>'名称已存在',
            'url.require'=>'路由地址不能为空',
            'url.unique'=>'路由地址已存在',
        ];

        $result=$this->validate($datas,$rules,$msg);
        if(true !== $result){
            $this->error($result);
        }

        $role_model=new Roles();
        $other_datas=$role_model->other_data(input());
        $datas=array_merge(input(),$other_datas);
        $role_model->isUpdate(true)->save($datas);
        if($role_model !== false){
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
        $res=Roles::withTrashed()->whereIn('id',$ids)->update(['status'=>$status,'updated_at'=>time()]);
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
                $count=Roles::field('id')->where('parent_id',$id)->count();
                if($count){
                    $fail_ids[]=$id;
                }else{
                    $del_ids[]=$id;
                }
            }
            $res=Roles::destroy($del_ids);
            $fail_num=count($fail_ids);
            if($res){
                if($fail_num){
                    return $this->success('部分删除成功！部分存在下级角色，请先删除其全部下级角色后重试！','');
                }else{
                    return $this->success('删除成功','');
                }
            }else{
                return $this->error('删除失败');
            }
        }else{
            $count=Roles::field('id')->where('parent_id',$ids)->count();
            if($count){
                return $this->error('其下存在下级角色，请先删除全部下级角色后重试！');
            }
            $res=Roles::destroy($ids);
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

        $res=Db::table('role')->whereIn('id',$ids)->where('`deleted_at` is not null')->update(['deleted_at'=>null,'updated_at'=>time()]);
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
        $res=Roles::onlyTrashed()->whereIn('id',$ids)->delete(true);
        if($res){
            return $this->success('销毁成功','');
        }else{
            return $this->error('销毁失败');
        }
    }
}
