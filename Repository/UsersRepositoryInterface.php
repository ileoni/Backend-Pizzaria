<?php

namespace App\Repository;

use Illuminate\Http\Request;

interface UsersRepositoryInterface
{
    public function register(Request $request);
    public function update(Request $request, $id);
    public function list();
    public function remove($id);
    public function findById($id);
}