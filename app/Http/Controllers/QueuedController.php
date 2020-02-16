<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\Queue;
class QueuedController extends Controller
{
    public function Test(){

        $arr=array(
            'time'=>time()
        );

        $this->dispatch(new Queue($arr));
        echo "成功";
    }
}
