<?php
/* |------------------------------------------------------
 * | 小说分类管理模型
 * |------------------------------------------------------
 * | 分类去空
 * | 添加
 * | 修改
 * | 删除
 * */
namespace app\system\model;
use think\Model;
use traits\model\SoftDelete;

class Bookcates extends Model
{
    use SoftDelete;
    protected $table = 'book_cate';
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $deleteTime='deleted_at';
    protected $autoWriteTimestamp = true;
    protected $field = true;
    public function setNameAttr($value)
    {
        return trim($value);
    }

    public function add(){
        $model = new Bookcates;
        $model->data = input();
        if($model->save()){
            return true;
        }else{
            return false;
        }
    }
    public function updata(){
        $model = new Bookcates;
        $id = input('id');
        $rs = $model->allowField(['name','sort'])->save($_POST, ['id' => $id]);
        if($rs){
            return true;
        }else{
            return false;
        }
    }
    public function dels(){
        $id = input('id');
        $model = Bookcates::get($id);
        $rs = $model->delete();
        if($rs){
            return true;
        }else{
            return false;
        }
    }
}