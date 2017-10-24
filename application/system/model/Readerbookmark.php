<?php
/* |------------------------------------------------------
 * | 读者书签管理模型
 * |------------------------------------------------------
 * */
namespace app\system\model;
use think\Model;

class Readerbookmark extends Model
{
    protected $table = 'reader_bookmark';
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $autoWriteTimestamp = true;
    protected $type = [
        'read_at'  =>  'timestamp'
    ];
}