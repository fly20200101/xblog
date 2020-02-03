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

    /**
     * @return mixed
     */
    public function getCmodel()
    {
        return $this->cmodel;
    }

    /**
     * @param mixed $cmodel
     */
    public function setCmodel($cmodel): void
    {
        $this->cmodel = $cmodel;
    }

    public function getPageList($page_obj, $filter, $sort)
    {
        $this->cmodel->getPageList($page_obj, $filter, $sort);
    }
}
