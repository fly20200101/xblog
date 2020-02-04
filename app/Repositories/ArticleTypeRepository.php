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
        return $this->cmodel->list($page_obj, $filter, $sort);
    }

    public function getArticleTypeInfo(int $id){
        return $this->cmodel->getRowById($id);
    }

    public function editArticleType(array $map,array $data){
        return $this->cmodel->edit($map,$data);
    }
}