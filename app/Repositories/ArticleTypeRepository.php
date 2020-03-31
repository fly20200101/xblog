<?php


namespace App\Repositories;


use App\Models\ArticleTypeModel;

/**
 * Class ArticleTypeRepository
 * @package App\Repositories
 */
class ArticleTypeRepository extends CommentRepository
{
    /**
     * @var ArticleTypeModel
     */
    protected $cmodel;

    /**
     * ArticleTypeRepository constructor.
     * @param ArticleTypeModel $articleTypeModel
     */
    public function __construct(ArticleTypeModel $articleTypeModel)
    {
        $this->cmodel = $articleTypeModel;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function addArticleType(array $data){
         if($this->cmodel->add($data)){
             return true;
         }else{
             return false;
         }
    }

    public function getPageList($page_obj, $filter, $sort){
        return $this->cmodel->getPageList($page_obj, $filter, $sort);
    }

    public function getArticleTypeInfo(int $id){
        return $this->cmodel->getRowById($id);
    }

    public function editArticleType(array $map,array $data){
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