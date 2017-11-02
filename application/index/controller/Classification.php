<?php
/* |------------------------------------------------------
 * | 前台----分类管理
 * |------------------------------------------------------
 * |
 * | 分类首页
 * | 分类列表页获取
 * */
namespace app\index\controller;

class Classification extends Auth
{
    public $data_setting;
    public function _initialize()
    {
        parent::_initialize();
        $this->data_setting=db('data_setting')->field(['ranking_top','ranking_total','pagenum'])->find();
    }

    /* ============ 分类首页 ============== */
    public function index()
    {
        /*+++++ 所有分类 +++++*/
        $bookcate_model = db('book_cate');
        $bookcate_name = $bookcate_model->field('id,name')->select();
        $datas['cate_name'] = $bookcate_name;
        $datas['pagenum'] = $this->data_setting['pagenum'];
        $this->assign($datas);
        return view();
    }

    /* ============ 分类列表页 ============== */
    public function cate_list()
    {
        /*+++++ 分类查询 +++++*/
        $where = [];
        /*----- 查询条数 -----*/
        $fenye_conut = input('fenye_conut');
        if($fenye_conut){
            $pagenum = $this->data_setting['pagenum']*$fenye_conut;
        }else{
            $pagenum = $this->data_setting['pagenum'];
        }
        /*----- 查询条件(分类) -----*/
        $cate_id = input('cate_id');
        if(is_numeric($cate_id)){
            $where['cate_id'] = "$cate_id";
            $datas['cate_id'] = $cate_id;
        }
        /*----- 查询条件(状态) -----*/
        $status = input('status');
        if(in_array($status,[0,1])&&is_numeric($status)){
            $where['status'] = "$status";
            $datas['status'] = $status;
        }
        /*----- 查询条件(类型) -----*/
        $type = input('type');
        if(in_array($type,[0,1])&&is_numeric($type)){
            $where['type'] = "$type";
            $datas['type'] = $type;
        }
        $book_list = model('books')
            ->field(['b.id','b.picture','b.title','a.name as author_name','b.summary'])
            ->alias('b')
            ->join('author a','b.author_id = a.id','left')
            ->where($where)
            ->where('online',1)
            ->paginate($pagenum);
       if($book_list){
           $this->success('获取成功','',$book_list);
       }else{
           $this->error('获取失败','');
       }
    }
}