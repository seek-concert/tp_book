<?php
/* |------------------------------------------------------
 * | 作者管理模型
 * |------------------------------------------------------
 * | 列表
 * | 添加
 * | 修改
 * | 删除
 * */
namespace app\system\model;
use think\Model;

class Authors extends Model
{
    protected $table = 'author';
    protected $field = true;
    public function add(){
        $model = new Authors;
        $model->data = input('');
        if($model->save()){
            return true;
        }else{
           return false;
        }
    }
}