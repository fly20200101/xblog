<?php

namespace App\Http\Controllers\Admin;


use App\Helpers\PageHelper;
use App\Http\Controllers\Controller;
use App\Repositories\ArticleTypeRepository;
use Illuminate\Http\Request;
use App\Traits\JsonTrait;
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

            $other = array(
                "count" => $page_obj->total_num,
                "curr_page" => $page_obj->curr_page,
                "total_page" => $page_obj->total_page,
                "total" => $page_obj->total_num,
            );
            return $this->json(0, "ok", $list, $other);
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
}
