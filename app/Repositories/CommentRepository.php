<?php


namespace App\Repositories;


/**
 * Class CommentRepository
 * @package App\Repositories
 */
class CommentRepository implements CommentRepositoryInterface
{
    /**
     * @var
     */
    protected $cmodel;

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->cmodel->getAll();
    }

    /**
     * @param array $map
     * @return mixed
     */
    public function getRow(array $map)
    {
        return $this->cmodel->getRow($map);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getRowById(int $id)
    {
        return $this->cmodel->getRowById($id);
    }

    public function insertData(array $data)
    {
        return $this->cmodel->create($data);
    }



    public function getPageList($page_obj, $filter, $sort)
    {
        return $this->cmodel->getPageList($page_obj, $filter, $sort);
    }

    public function edit(array $map, array $data)
    {
        return $this->cmodel->edit($map,$data);
    }

    public function del(array $map){
        return $this->cmodel->del($map);
    }

    public function reduction(array $map)
    {
        return $this->cmodel->reduction($map);
    }

    public function with_trashed(array $map)
    {
        return $this->cmodel->withTrashed($map);
    }

    public function only_trashed(array $map)
    {
        return $this->cmodel->only_trashed($map);
    }
}
