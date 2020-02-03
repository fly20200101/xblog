<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ArticleTypeRepository;
use Illuminate\Http\Request;

/**
 * Class ArticleController
 * @package App\Http\Controllers\Admin
 */
class ArticleController extends BaseController
{
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
            $list = $this->articleTypeRepository->getPageList(10, [], 'at_id');
            return json_encode(["code"=>0,"msg"=>'成功',"data"=>$list['data']]);
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
            var_dump($typename);
            if($this->articleTypeRepository->addArticleType(['type_name'=>$typename])){
                return json_encode(['status'=>true,'code'=>900003,'message'=>'添加分类成功','data'=>[]]);
            }else{
                return json_encode(['status'=>false,'code'=>800006,'message'=>'添加分类失败','data'=>[]]);
            }
        }else{
            return view('admin/add_article_type');
        }
    }
}
