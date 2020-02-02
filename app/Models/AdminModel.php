<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminModel
 * @package App\Models
 */
class AdminModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'fb_admin';
    /**
     * @var string
     */
    protected $primaryKey = 'admin_id';

    /**
     *
     */
    public function getAll(){
    }

    /**
     * @param array $map
     * @return mixed
     */
    public function getRow(array $map){
        return $this->where($map)->get()->toArray();
    }
}
