<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function basePageList($where,$currPage,$pageSize,$sort=array(),$infield='',$inarray=[]){

        if(empty($sort)){
            $sort['sort_field'] = "id";
            $sort['sort_order'] = "desc";
        }
        if($infield && $inarray){
            return  $this->where($where)->wherein($infield,$inarray)
                ->orderBy($sort['sort_field'],$sort['sort_order'])
                ->limit($pageSize)->offset(($currPage - 1) * $pageSize)->get()->toArray();
        }else{
            return $this->where($where)
                ->orderBy($sort['sort_field'],$sort['sort_order'])
                ->limit($pageSize)->offset(($currPage - 1) * $pageSize)->get()->toArray();
        }

    }
}
