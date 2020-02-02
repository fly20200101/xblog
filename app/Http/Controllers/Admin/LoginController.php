<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AdminRepository;
use Illuminate\Http\Request;

/**
 * Class LoginController
 * @package App\Http\Controllers\Admin
 */
class LoginController extends BaseController
{
    /**
     * @var AdminRepository
     */
    protected $adminRepository;

    /**
     * LoginController constructor.
     * @param AdminRepository $adminRepository
     */
    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    /**
     * 登录
     * @param Request $request
     * @return false|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function Login(Request $request){
        if($request->ajax()){
            $admin_username = trim($request->input('admin_username',''));
            $admin_login_pwd = trim($request->input('admin_login_pwd',''));
            if(empty($admin_username)){
                return json_encode(['status'=>false,'code'=>800001,'message'=>'管理员用户名不能为空','data'=>[]]);
            }
            if(empty($admin_login_pwd)){
                return json_encode(['status'=>false,'code'=>800002,'message'=>'管理员密码不能为空','data'=>[]]);
            }
            if($this->adminRepository->doLogin(['admin_username'=>$admin_username,'admin_login_pwd'=>md5($admin_login_pwd)])){

                return json_encode(['status'=>true,'code'=>900001,'message'=>'登录成功','data'=>[]]);
            }else{
                return json_encode(['status'=>false,'code'=>800003,'message'=>'账号密码错误','data'=>[]]);
            }

        }else{
            return view('admin/login');
        }
    }


}
