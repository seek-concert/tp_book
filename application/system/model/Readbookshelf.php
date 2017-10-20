<?php
/* |------------------------------------------------------
 * | 读者书架管理模型
 * |------------------------------------------------------
 * */
namespace app\system\model;
use think\Model;

class Readbookshelf extends Model
{
    protected $table = 'reader_bookshelf';
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $autoWriteTimestamp = true;
    protected $type = [
        'read_at'  =>  'timestamp'
    ];
}