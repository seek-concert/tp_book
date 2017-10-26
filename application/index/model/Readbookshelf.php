<?php
/* |------------------------------------------------------
 * | 读者书架管理模型
 * |------------------------------------------------------
 * */
namespace app\index\model;
use think\Model;

class Readbookshelf extends Model
{
    protected $table = 'reader_bookshelf';
    protected $type = [
        'created_at'  =>  'timestamp',
        'updated_at'  =>  'timestamp',
        'read_at'  =>  'timestamp'
    ];
}