<?php


namespace App\Repositories;


interface CommentRepositoryInterface
{
    public function getAll();
    public function getRow(array $map);
    public function getRowById(int $id);
}
