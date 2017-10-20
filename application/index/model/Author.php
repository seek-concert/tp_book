<?php
/* |------------------------------------------------------
 * | 作者管理模型
 * |------------------------------------------------------
 * | 笔名去空
 * | 真实姓名去空
 * | 添加
 * | 修改
 * */
namespace app\index\model;
use think\Model;

class Author extends Model
{
    protected $table = 'author';
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $deleteTime='deleted_at';
    protected $autoWriteTimestamp = true;
}