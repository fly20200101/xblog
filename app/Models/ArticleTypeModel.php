<?php

namespace App\Models;

use App\Helpers\PageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleTypeModel extends BaseModel
{
    protected $table = 'fb_article_type_models';

    protected $primaryKey = 'at_id';
    use SoftDeletes;
    public function add(array $data){
        return $this->insert($data);
    }

    public function getPageList(PageHelper $page_obj = null, $filter = array(), $sort = array()){
        $where = self::getWhere($filter);
        $order = "";
        $limit = "";
        if (empty($sort)) {
            $sort['sort_field'] = "at_id";
            $sort['sort_order'] = "desc";
        }
        if (empty($page_obj)) {
            return array();
        }

        $count = $this->count($where);

        $page_obj->set_count($count);

        if (empty($count)) {
            return array();
        }
        $limit = $page_obj->page_size;
        $page = $page_obj->curr_page;

        return $this->basePageList($where, $page, $limit,$sort);
    }

    public function getRowById(int $id){
        return $this->where(['at_id'=>$id])->get()->toArray();
    }

    public function edit(array $map,array $data){
        return $this->where($map)->update($data);
    }

    public static function getWhere($filter){
        $where = [];

        if (isset($filter['type_name']) AND $filter['type_name']) {
            $where[] = ['type_name', 'LIKE', '%'.$filter['type_name'].'%'];
        }

        return $where;
    }

    public function count($where,$infield='',$inarray=[]){
        if($infield && $inarray){
            return $this->where($where)->wherein($infield,$inarray)->whereNull('deleted_at')->count();
        }else{
            return $this->where($where)->whereNull('deleted_at')->count();
        }
    }

    public function del(array $map){
        return $this->where($map)->delete();
    }
}
