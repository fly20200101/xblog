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
}