<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\AdminLogJob;
use App\Models\AdminLogModel;
use App\Repositories\AdminRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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
                AdminLogJob::dispatch([
                    "op_type_id" => AdminLogModel::OPERATE_TYPE_LOGIN,       //操作类型 ，增加，删除， 修改，登录
                    "op_user_id" => $this->uid, //操作者id
                    "op_user_name" => $admin_username,//操作者账号
                    "be_object_id" => '',//操作对象id
                    "be_object_name" => '', //被操作对象名字或者简称
                    "level" => AdminLogModel::LEVEL_INFO, //日志级别
                    "title" => "管理员{$admin_username}登录",//日志标题
                    "desc" => "", //日志描述
                    "op_time" => time(),//事件执行时间
                    "op_ip" => 0,//日志操作者ip
                    "op_url" => $request->getRequestUri(),
                    "input" => [],
                ]);
                return json_encode(['status'=>true,'code'=>900001,'message'=>'登录成功','data'=>[]]);
            }else{
                return json_encode(['status'=>false,'code'=>800003,'message'=>'账号密码错误','data'=>[]]);
            }

        }else{
            return view('admin/login');
        }
    }

    /**
     * 退出登录
     * @param Request $request
     * @return false|string
     */
    public function logout(Request $request){
        if($request->ajax()){
            $act = trim($request->input('act',''));
            if($act == 'logout'){
                session()->forget('admin_info');
                return json_encode(['status'=>true,'code'=>900002,'message'=>'退出成功','data'=>[]]);
            }else{
                return json_encode(['status'=>false,'code'=>800004,'message'=>'退出失败','data'=>[]]);
            }
        }
    }
}
