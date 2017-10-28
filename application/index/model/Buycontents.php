<?php
/* |------------------------------------------------------
 * | 购买章节记录管理模型
 * |------------------------------------------------------
 * */
namespace app\index\model;
use think\Model;

class Buycontents extends Model
{
    protected $table = 'buy_content';
    protected $createTime = 'created_at';
    protected $autoWriteTimestamp = true;
    protected $field = true;
}