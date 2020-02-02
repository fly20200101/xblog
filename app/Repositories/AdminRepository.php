<?php


namespace App\Repositories;


use App\Models\AdminModel;

class AdminRepository extends CommentRepository
{
    protected $cmodel;
    public function __construct(AdminModel $adminModel)
    {
        $this->cmodel = $adminModel;
    }

    public function doLogin(array $map){
       $info = $this->getRow($map);
       if(empty($info)){
           return false;
       }else{
           return true;
       }
    }
}
