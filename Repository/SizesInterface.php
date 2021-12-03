<?php

namespace App\Repository;

interface SizesInterface
{
    public function list();
    public function findById($id);
}