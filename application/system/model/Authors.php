<?php
/* |------------------------------------------------------
 * | 作者管理模型
 * |------------------------------------------------------
 * | 笔名去空
 * | 真实姓名去空
 * | 添加
 * | 修改
 * | 删除
 * */
namespace app\system\model;
use think\Model;
use traits\model\SoftDelete;

class Authors extends Model
{
    use SoftDelete;
    protected $table = 'author';
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $deleteTime='deleted_at';
    protected $autoWriteTimestamp = true;
    protected $field = true;
    public function setNameAttr($value)
    {
        return trim($value);
    }

    public function setRealnameAttr($value)
    {
        return trim($value);
    }

    public function add(){
        $this->data = input();
        if($this->save()){
            return true;
        }else{
           return false;
        }
    }
    public function updata(){
        $id = input('id');
        $rs = $this->allowField(['name','realname','phone'])->save($_POST, ['id' => $id]);
        if($rs){
            return true;
        }else{
            return false;
        }
    }
}