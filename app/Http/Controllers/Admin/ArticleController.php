<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArticleController extends BaseController
{
    public function ArticleTypeList(){
        return view('admin/article_type_list');
    }


}
