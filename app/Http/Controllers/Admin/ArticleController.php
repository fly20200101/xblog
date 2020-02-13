<?php

namespace App\Http\Controllers\Admin;


use App\Helpers\PageHelper;
use App\Http\Controllers\Controller;
use App\Jobs\AdminLogJob;
use App\Models\AdminLogModel;
use App\Repositories\ArticleTypeRepository;
use Illuminate\Http\Request;
use App\Traits\JsonTrait;
use Illuminate\Support\Arr;

/**
 * Class ArticleController
 * @package App\Http\Controllers\Admin
 */
class ArticleController extends BaseController
{
    use JsonTrait;
    /**
     * @var ArticleTypeRepository
     */
    protected $articleTypeRepository;

    /**
     * ArticleController constructor.
     * @param ArticleTypeRepository $articleTypeRepository
     */
    public function __construct(ArticleTypeRepository $articleTypeRepository)
    {
        $this->articleTypeRepository = $articleTypeRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ArticleTypeList(Request $request){
        if($request->ajax()){
            $page = intval($request->input("page", 1));
            $pageSize = intval($request->input("limit", 10));
            $page_obj = new PageHelper($page, $pageSize);

            $filter = [];
            $sort = array();
            $list = $this->articleTypeRepository->getPageList($page_obj, $filter, $sort);
            return json_encode(['status'=>true,'code'=>0,'count'=>count($list),'data'=>$list,'msg'=>'']);

        }else{
            return view('admin/article_type_list');
        }
    }

    /**
     * 添加文章类型
     * @param Request $request
     * @return false|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function addArticleType(Request $request){
        if($request->ajax()){
            $typename = trim($request->input('type_name',''));
            if(empty($typename)){
                return json_encode(['status'=>false,'code'=>800005,'message'=>'分类名称不能为空','data'=>[]]);
            }
            if($this->articleTypeRepository->addArticleType(['type_name'=>$typename])){
                return json_encode(['status'=>true,'code'=>900003,'message'=>'添加分类成功','data'=>[]]);
            }else{
                return json_encode(['status'=>false,'code'=>800006,'message'=>'添加分类失败','data'=>[]]);
            }
        }else{
            return view('admin/add_article_type');
        }
    }

    public function editArticleType(Request $request){
        if($request->ajax()){
            $type_name = trim($request->input('type_name',''));
            $sort = trim($request->input('sort',0));
            $at_id = trim($request->input('at_id'));
            if(empty($type_name)){
                return json_encode(['status'=>false,'code'=>800005,'message'=>'分类名称不能为空','data'=>[]]);
            }

            if($this->articleTypeRepository->editArticleType(['at_id'=>$at_id],['type_name'=>$type_name,'sort'=>$sort])){
                return json_encode(['status'=>true,'code'=>900004,'message'=>'修改分类成功','data'=>[]]);
            }else{
                return json_encode(['status'=>false,'code'=>800007,'message'=>'修改分类成功','data'=>[]]);
            }

        }else{
            $at_id = trim($request->input('at_id'));
            $type_info = $this->articleTypeRepository->getRowById($at_id);
            return view('admin/edit_article_type',['data'=>$type_info[0]]);
        }
    }

    public function addChildArticleType(Request $request){
        if($request->ajax()){
            $type_name = trim($request->input('type_name',''));
            $pid = trim($request->input('at_id',''));
            if($pid<=0){
                return json_encode(['status'=>false,'code'=>800009,'message'=>'参数错误','data'=>[]]);
            }
            $sort = trim($request->input('sort',0));
            if(empty($type_name)){
                return json_encode(['status'=>false,'code'=>800008,'message'=>'分类名称不能为空','data'=>[]]);
            }
            if($this->articleTypeRepository->addArticleType(['type_name'=>$type_name,'pid'=>$pid,'sort'=>$sort])){
                return json_encode(['status'=>true,'code'=>900005,'message'=>'添加分类成功','data'=>[]]);
            }else{
                return json_encode(['status'=>false,'code'=>800006,'message'=>'添加分类失败','data'=>[]]);
            }
        }else{
            $at_id = trim($request->input('at_id'));
            $data['at_id'] = $at_id;
            return view("admin/add_child_article_type",['data'=>$data]);
        }
    }

    public function delArticleType(Request $request){
        $act = trim($request->input('act',''));
        $at_id = trim($request->input('at_id',''));
        if(empty($act)){
            return json_encode(['status'=>false,'code'=>800009,'message'=>'参数错误','data'=>[]]);
        }
        if(empty($at_id)){
            return json_encode(['status'=>false,'code'=>800009,'message'=>'参数错误','data'=>[]]);
        }
        if($this->articleTypeRepository->del(['at_id'=>$at_id])){
            $info = $this->articleTypeRepository->getRowById($at_id);
            AdminLogJob::dispatch([
                "op_type_id" => AdminLogModel::OPERATE_TYPE_DELETE,       //操作类型 ，增加，删除， 修改，登录
                "op_user_id" => $this->uid, //操作者id
                "op_user_name" => Arr::get($this->userInfo, 'account', ''),//操作者账号
                "be_object_id" => $at_id,//操作对象id
                "be_object_name" => '名字' . Arr::get($mongo_data, 'type_name', ''), //被操作对象名字或者简称
                "level" => AdminLogModel::LEVEL_WARNING, //日志级别
                "title" => '删除文章分类',//日志标题
                "desc" => "", //日志描述
                "op_time" => time(),//事件执行时间
                "op_ip" => $this->clientIp,//日志操作者ip
                "op_url" => $request->getRequestUri(),
                "input" => [],
            ]);
            return json_encode(['status'=>true,'code'=>900006,'message'=>'删除成功','data'=>[]]);
        }else{
            return json_encode(['status'=>false,'code'=>800010,'message'=>'删除失败','data'=>[]]);
        }
    }

    // 回收站列表

    public function article_type_recycle_bin(Request $request){
        if($request->ajax()){
            $page = intval($request->input("page", 1));
            $pageSize = intval($request->input("limit", 10));
            $page_obj = new PageHelper($page, $pageSize);

            $filter = [];
            $sort = array();
            $list = $this->articleTypeRepository->getPageList($page_obj, $filter, $sort);
            return json_encode(['status'=>true,'code'=>0,'count'=>count($list),'data'=>$list,'msg'=>'']);

        }else{
            return view('admin/article_type_recycle_bin_list');
        }
    }
}
