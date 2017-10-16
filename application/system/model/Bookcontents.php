<?php
/* |------------------------------------------------------
 * | 小说章节内容管理模型
 * |------------------------------------------------------
 * | 小说标题去空
 * | 添加
 * | 修改
 * | 删除
 * */
namespace app\system\model;
use think\Model;
use traits\model\SoftDelete;

class Bookcontents extends Model
{
    use SoftDelete;
    protected $table = 'book_content';
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $deleteTime='deleted_at';
    protected $autoWriteTimestamp = true;
    protected $field = true;
    /*----- 关联小说 -----*/
    public function Books()
    {
        return $this->belongsTo('Books','book_id');
    }
    public function add(){
        $data = input();
        $data['edited_at']= time();
        return $data;
    }
    public function updata(){
        $id = input('id');
        $rs = $this->save($_POST, ['id' => $id]);
        if($rs){
            return true;
        }else{
            return false;
        }
    }
}