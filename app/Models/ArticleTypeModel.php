<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleTypeModel extends Model
{
    protected $table = 'fb_article_type';

    protected $primaryKey = 'at_id';

    public function add(array $data){
        return $this->insert($data);
    }
}
