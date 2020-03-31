<?php


namespace App\Repositories;


use App\Models\ArticleModel;

class ArticleRepository extends CommentRepository
{
    protected $cmodel;

    public function __construct(ArticleModel $articleModel)
    {
        $this->cmodel = $articleModel;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function addArticle(array $data){
        if($this->cmodel->add($data)){
            return true;
        }else{
            return false;
        }
    }

    public function getPageList($page_obj, $filter, $sort){
        return $this->cmodel->getPageList($page_obj, $filter, $sort);
    }

    public function getArticleInfo(int $id){
        return $this->cmodel->getRowById($id);
    }

    public function editArticle(array $map,array $data){
        return $this->cmodel->edit($map,$data);
    }

    public function del(array $map){
        return $this->cmodel->del($map);
    }

    public function reduction(array $map)
    {
        return $this->cmodel->reduction($map);
    }

    public function with_trashed(array $map=[]){
        return $this->cmodel->with_trashed($map);
    }

    public function only_trashed(array $map=[]){
        return $this->cmodel->only_trashed($map);
    }

    public function getAll(){
        return $this->cmodel->getAll();
    }
}