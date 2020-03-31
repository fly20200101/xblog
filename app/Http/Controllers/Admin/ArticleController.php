<?php

namespace App\Http\Controllers\Admin;


use App\Helpers\PageHelper;
use App\Http\Controllers\Controller;
use App\Jobs\AdminLogJob;
use App\Models\AdminLogModel;
use App\Repositories\ArticleRepository;
use App\Repositories\ArticleTypeRepository;
use App\Traits\ArraySort;
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
    use ArraySort;
    /**
     * @var ArticleTypeRepository
     */
    protected $articleTypeRepository;
    /**
     * @var ArticleRepository
     */
    protected $articleRepository;

    /**
     * ArticleController constructor.
     * @param ArticleTypeRepository $articleTypeRepository
     * @param ArticleRepository $articleRepository
     */
    public function __construct(ArticleTypeRepository $articleTypeRepository,ArticleRepository $articleRepository)
    {
        $this->articleTypeRepository = $articleTypeRepository;
        $this->articleRepository = $articleRepository;
    }

    /**
     * 文章类型列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ArticleTypeList(Request $request){
        if($request->ajax()){
            $page = intval($request->input("page", 1));
            $list = $this->articleTypeRepository->getAll();
            $count = count($list);
            $limit = $request->input('limit',10);
            $start=($page-1)*$limit;
            $total_page = ceil($count / $limit);
            $field = $request->input('field','');
            $order = $request->input('order','');
            if($field != '' && $order != ''){
                $list = $this->ArraySort($list,$field,$order);
                $list = array_slice($list,$start,$limit);
            }else{
                $list = array_slice($list,$start,$limit);
            }

            $other = array(
                "count" => $count,
                "curr_page" => $page,
                "total_page" => $total_page,
                "total" => $count,
            );
            return json_encode(['status'=>true,'code'=>0,'count'=>$count,'data'=>$list,'msg'=>'','other'=>$other]);


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

    /**
     * 编辑分类
     * @param Request $request
     * @return false|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
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

    /**
     * 加入子分类
     * @param Request $request
     * @return false|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
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

    /**
     * 软删除分类
     * @param Request $request
     * @return false|string
     */
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

            return json_encode(['status'=>true,'code'=>900006,'message'=>'删除成功','data'=>[]]);
        }else{
            return json_encode(['status'=>false,'code'=>800010,'message'=>'删除失败','data'=>[]]);
        }
    }


    /**
     * 回收站 - 列表
     * @param Request $request
     * @return false|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function articleTypeRecycleBinList(Request $request){
        if($request->ajax()){
            $page = intval($request->input("page", 1));
            $pageSize = intval($request->input("limit", 10));
            $list = $this->articleTypeRepository->only_trashed();
            $count = count($list);
            $limit = $request->input('limit',10);
            $start=($page-1)*$limit;
            $total_page = ceil($count / $limit);
            $field = $request->input('field','');
            $order = $request->input('order','');
            if($field != '' && $order != ''){
                $list = $this->ArraySort($list,$field,$order);
                $list = array_slice($list,$start,$limit);
            }else{
                $list = array_slice($list,$start,$limit);
            }

            $other = array(
                "count" => $count,
                "curr_page" => $page,
                "total_page" => $total_page,
                "total" => $count,
            );
            return json_encode(['status'=>true,'code'=>0,'count'=>$count,'data'=>$list,'msg'=>'','other'=>$other]);
        }else{
            return view('admin/article_type_recycle_bin_list');
        }
    }

    /**
     * 回收站 - 还原
     * @param Request $request
     * @return false|string
     */
    public function reduction_article_type(Request $request){
        if($request->ajax()){
            $do_act = $request->input('act','');
            $id = $request->input('at_id','');
            if(empty($do_act) || $do_act !== 'reduction'){
                return json_encode(['status'=>false,'code'=>800011,'message'=>'参数错误','data'=>[]]);
            }else{
                if($this->articleTypeRepository->reduction(['at_id'=>$id])){
                    return json_encode(['status'=>true,'code'=>900007,'message'=>'还原成功','data'=>[]]);
                }else{
                    return json_encode(['status'=>false,'code'=>800011,'message'=>'还原失败','data'=>[]]);
                }
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function articleList(Request $request){
        if($request->ajax()){
            $page = intval($request->input("page", 1));
            $list = $this->articleRepository->getAll();
            $count = count($list);
            $limit = $request->input('limit',10);
            $start=($page-1)*$limit;
            $total_page = ceil($count / $limit);
            $field = $request->input('field','');
            $order = $request->input('order','');
            if($field != '' && $order != ''){
                $list = $this->ArraySort($list,$field,$order);
                $list = array_slice($list,$start,$limit);
            }else{
                $list = array_slice($list,$start,$limit);
            }

            $other = array(
                "count" => $count,
                "curr_page" => $page,
                "total_page" => $total_page,
                "total" => $count,
            );
            return json_encode(['status'=>true,'code'=>0,'count'=>$count,'data'=>$list,'msg'=>'','other'=>$other]);
        }else{
            return view("admin/article/article_list");
        }
    }

    /**
     * @param Request $request
     * @return false|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function createArticle(Request $request){
        if($request->ajax()){
            $title = trim($request->input('title',''));
            $content = $request->input('content','');
            $at_id = intval($request->input('at_id',0));
            if($at_id == 0 ){
                return json_encode(['status'=>false,'code'=>800014,'message'=>'分类不能为空','data'=>[]]);
            }
            if(empty($title)){
                return json_encode(['status'=>false,'code'=>800012,'message'=>'标题不能为空','data'=>[]]);
            }
            if(empty($content)){
                return json_encode(['status'=>false,'code'=>800013,'message'=>'内容不能为空','data'=>[]]);
            }
            if($this->articleRepository->addArticle(['title'=>$title,'create_time'=>date('Y-m-d H:i:s'),'at_id'=>$at_id,'article_content'=>$content])){
                return json_encode(['status'=>true,'code'=>900007,'message'=>'发布成功','data'=>[]]);
            }else{
                return json_encode(['status'=>false,'code'=>800015,'message'=>'发布失败','data'=>[]]);
            }
        }else{
            $article_type_list = $this->articleTypeRepository->getAll();
            return view('admin/article/create_article',['data'=>$article_type_list]);
        }
    }

    public function editArticle(Request $request){
        if($request->ajax()){
            $id = intval($request->input('id',0));
            $title = trim($request->input('title',''));
            $content = $request->input('content','');
            $at_id = intval($request->input('at_id',0));
            if($at_id == 0 ){
                return json_encode(['status'=>false,'code'=>800014,'message'=>'分类不能为空','data'=>[]]);
            }
            if(empty($title)){
                return json_encode(['status'=>false,'code'=>800012,'message'=>'标题不能为空','data'=>[]]);
            }
            if(empty($content)){
                return json_encode(['status'=>false,'code'=>800013,'message'=>'内容不能为空','data'=>[]]);
            }
            if($this->articleRepository->editArticle(['id'=>$id],['title'=>$title,'at_id'=>$at_id,'article_content'=>$content])){
                return json_encode(['status'=>true,'code'=>900007,'message'=>'发布成功','data'=>[]]);
            }else{
                return json_encode(['status'=>false,'code'=>800015,'message'=>'发布失败','data'=>[]]);
            }
        }else{
            $id = intval($request->input('id',0));
            $info = $this->articleRepository->getRowById($id);
            $article_type_list = $this->articleTypeRepository->getAll();
            return view('admin.article.edit_article',['info'=>$info,'data'=>$article_type_list]);
        }
    }

    public function delArticle(Request $request){
        $act = trim($request->input('act',''));
        $id = trim($request->input('id',''));
        if(empty($act) || $act !== 'del'){
            return json_encode(['status'=>false,'code'=>800009,'message'=>'参数错误','data'=>[]]);
        }
        if(empty($id)){
            return json_encode(['status'=>false,'code'=>800009,'message'=>'参数错误','data'=>[]]);
        }

        if($this->articleRepository->del(['id'=>$id])){
            return json_encode(['status'=>true,'code'=>900006,'message'=>'删除成功','data'=>[]]);
        }else{
            return json_encode(['status'=>false,'code'=>800010,'message'=>'删除失败','data'=>[]]);
        }
    }

    /**
     * 回收站 - 列表
     * @param Request $request
     * @return false|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function articleRecycleBinList(Request $request){
        if($request->ajax()){
            $page = intval($request->input("page", 1));
            $pageSize = intval($request->input("limit", 10));
            $list = $this->articleRepository->only_trashed();
            $count = count($list);
            $limit = $request->input('limit',10);
            $start=($page-1)*$limit;
            $total_page = ceil($count / $limit);
            $field = $request->input('field','');
            $order = $request->input('order','');
            if($field != '' && $order != ''){
                $list = $this->ArraySort($list,$field,$order);
                $list = array_slice($list,$start,$limit);
            }else{
                $list = array_slice($list,$start,$limit);
            }

            $other = array(
                "count" => $count,
                "curr_page" => $page,
                "total_page" => $total_page,
                "total" => $count,
            );
            return json_encode(['status'=>true,'code'=>0,'count'=>$count,'data'=>$list,'msg'=>'','other'=>$other]);
        }else{
            return view('admin/article/article_recycle_bin_list');
        }
    }

    /**
     * 回收站 - 还原
     * @param Request $request
     * @return false|string
     */
    public function reduction_article(Request $request){
        if($request->ajax()){
            $do_act = $request->input('act','');
            $id = $request->input('id','');
            if(empty($do_act) || $do_act !== 'reduction'){
                return json_encode(['status'=>false,'code'=>800011,'message'=>'参数错误','data'=>[]]);
            }else{
                if($this->articleRepository->reduction(['id'=>$id])){
                    return json_encode(['status'=>true,'code'=>900007,'message'=>'还原成功','data'=>[]]);
                }else{
                    return json_encode(['status'=>false,'code'=>800011,'message'=>'还原失败','data'=>[]]);
                }
            }
        }
    }
}
