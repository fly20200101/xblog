<?php


namespace App\Repositories;


use App\Models\AdminModel;

/**
 * Class AdminRepository
 * @package App\Repositories
 */
class AdminRepository extends CommentRepository
{
    /**
     * @var AdminModel
     */
    protected $cmodel;

    /**
     * AdminRepository constructor.
     * @param AdminModel $adminModel
     */
    public function __construct(AdminModel $adminModel)
    {
        $this->cmodel = $adminModel;
    }

    /**
     * @param array $map
     * @return bool
     */
    public function doLogin(array $map){
       $info = $this->getRow($map);
       if(empty($info)){
           return false;
       }else{
           session(['admin_info'=>$info]);
           return true;
       }
    }
}
