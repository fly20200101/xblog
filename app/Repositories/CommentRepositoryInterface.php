<?php


namespace App\Repositories;


interface CommentRepositoryInterface
{
    public function getAll();
    public function getRow(array $map);
    public function getRowById(int $id);
    public function insertData(array $data);
    public function getPageList($page_obj, $filter, $sort);
}
