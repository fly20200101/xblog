<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public $uid;
    public $admin_info;
    public $lientIp=0;
    public function __construct()
    {
        if(session('admin_info')){
            $this->uid = session('admin_info')[0]['admin_id'];
            $this->admin_info = session('admin_info')[0];
        }
        //$this->lientIp = $this->getIp();
    }

    public function json_success(int $code,array $data=[]){

    }

    protected function checkLogin(){

    }


}
