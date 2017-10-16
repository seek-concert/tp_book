<?php
/* |------------------------------------------------------
 * | 小说章节内容管理
 * |------------------------------------------------------
 * | 列表
 * | 添加
 * | 详情
 * | 修改
 * | 删除
 * */
namespace app\system\controller;
use app\system\model\Bookcontents;
use app\system\model\Books;
use think\Db;

class Bookcontent extends Auth
{
    /* ========== 列表 ========== */
    public function index()
    {
        $bookcontent_model = model('Bookcontents');
        $where = [];
        /* ----- 查询条件(小说名称) -----*/
        $title = input('title');
        if($title){
            $where['title'] = array('LIKE',"%$title%");
            $this->assign('title',$title);
        }
        $where['book_id'] = input('book_id');
        $bookcontent_list = $bookcontent_model
            ->where($where)
            ->paginate();
        $this->assign('bookcontent_list',$bookcontent_list);
        return view();
    }

    /* ========== 添加 ========== */
    public function insert()
    {
        $book_id = input('book_id');
        if(empty($book_id)){
            $this->error('非法操作','');
        }
        $this->assign('book_id',$book_id);
        if(request()->isPost()){
            $datas = input();
            $rule = [
                ['order_num', 'require|number|unique:book_content,book_id='.$book_id.'&order_num='.$datas['order_num'], '请填写章节号|章节号必须为数字|章节号重复,请确认后填写'],
                ['name', 'require', '请填写章节标题'],
                ['content', 'require', '请填写章节内容'],
                ['price', 'require|number', '请填写章节价格|章节价格必须为数字']
            ];
            $result = $this->validate($datas, $rule);
            if (true !== $result) {
                $this->error($result);
            }
            Db::startTrans();
            try{
                $bookcontent_model = new Bookcontents;
                $book_model = new Books;
                $data = $bookcontent_model->add();
                $bookcontent_model->save($data);
                $rs=$book_model->where('id',$book_id)->update(['edited_at'=>$data['edited_at']]);
                Db::commit();
            } catch (\Exception $e) {
                $rs='';
                Db::rollback();
            }
            if($rs){
               return $this->success('添加成功','');
            }else{
               return $this->error('添加失败','');
            }
        }else{
            return view('modify');
        }
    }

    /* ========== 详情 ========== */
    public function detail(){
        $id = input('id');
        if(empty($id)){
            return $this->error('非法操作','');
        }
        $book_id = input('book_id');
        if(empty($book_id)){
            return $this->error('非法操作','');
        }
        $this->assign('book_id',$book_id);
        $where['id'] = $id;
        $info = model('Bookcontents')->where($where)->find();
        $this->assign('info',$info);
        return view('modify');
    }

    /* ========== 修改 ========== */
    public function modify(){
        $id = input('id');
        if(empty($id)){
            return $this->error('非法操作','');
        }
        $book_id = input('book_id');
        if(empty($book_id)){
            $this->error('非法操作','');
        }
        $this->assign('book_id',$book_id);
        $datas = input();
        $rule = [
            ['order_num', 'require|number|unique:book_content,book_id='.$book_id.'&order_num='.$datas['order_num'], '请填写章节号|章节号必须为数字|章节号重复,请确认后填写'],
            ['name', 'require', '请填写章节标题'],
            ['content', 'require', '请填写章节内容'],
            ['price', 'require|number', '请填写章节价格|章节价格必须为数字']
        ];
        $result = $this->validate($datas, $rule);
        if (true !== $result) {
            $this->error($result);
        }
        $bookcontent_model = new Bookcontents;
        $rs = $bookcontent_model->updata();
        if ($rs) {
            return  $this->success('更新成功', '');
        } else {
            return  $this->error('更新失败', '');
        }
    }

    /* ========== 删除 ========== */
    public function del(){
        $id = input('id');
        if(empty($id)){
            return $this->error('非法操作','');
        }
        $bookcontent_model = new Bookcontents;
        $rs = $bookcontent_model->dels();
        if ($rs) {
            return  $this->success('删除成功', '');
        } else {
            return  $this->error('删除失败', '');
        }
    }
}