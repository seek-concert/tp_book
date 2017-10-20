<?php
/* |------------------------------------------------------
 * | 小说管理模型
 * |------------------------------------------------------
 * */
namespace app\index\model;
use think\Model;

class Books extends Model
{
    protected $table = 'book';
    protected $pk='id';
    protected $createTime='created_at';
    protected $updateTime='updated_at';
    protected $autoWriteTimestamp = true;
    protected $field=true;
    protected $type = [
        'created_at'  =>  'timestamp',
        'updated_at'  =>  'timestamp',
        'deleted_at'  =>  'timestamp'
    ];

    /*----- 关联小说作者 -----*/
    public function Author()
    {
        return $this->belongsTo('Authors','author_id');
    }
}