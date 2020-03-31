<?php


namespace App\Repositories;


interface CommentRepositoryInterface
{
    public function getAll();
    public function getRow(array $map);
    public function getRowById(int $id);
    public function insertData(array $data);
    public function getPageList($page_obj, $filter, $sort);
    public function edit(array $map,array $data);
    public function del(array $map);
    public function reduction(array $map);
    public function with_trashed(array $map);
    public function only_trashed(array $map);
}
