<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AdminModel extends Model
{
    protected $table = 'fb_admin';
    protected $primaryKey = 'admin_id';

    public function getAll(){
    }
    public function getRow(array $map){
        return $this->where($map)->get()->toArray();
    }
}
