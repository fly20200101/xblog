<?php


namespace App\Repositories;


class CommentRepository implements CommentRepositoryInterface
{
    protected $cmodel;
    public function getAll()
    {
        return $this->cmodel->getAll();
    }

    public function getRow(array $map)
    {
        return $this->cmodel->getRow($map);
    }

    public function getRowById(int $id)
    {
        return $this->cmodel->getRowById($id);
    }
}
