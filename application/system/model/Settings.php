<?php
/* |------------------------------------------------------
 * | 应用设置 模型
 * |------------------------------------------------------
 * */
namespace app\system\model;

use think\Model;

class Settings extends Model
{
    protected $table='setting';
    protected $pk='id';
    protected $createTime='created_at';
    protected $updateTime='updated_at';
    protected $autoWriteTimestamp = true;
    protected $field=true;

    public function setNameAttr($value)
    {
        return trim($value);
    }

    public function setAppidAttr($value)
    {
        return trim($value);
    }

    public function setAppsecretAttr($value)
    {
        return trim($value);
    }

    public function setMchidAttr($value)
    {
        return trim($value);
    }

    public function setMchkeyAttr($value)
    {
        return trim($value);
    }

    public function setTitlettr($value)
    {
        return trim($value);
    }

    public function setKeywordAttr($value)
    {
        return trim($value);
    }
}