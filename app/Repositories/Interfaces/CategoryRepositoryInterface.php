<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface CategoryRepositoryInterface
{
    public function getAllCategories(Request $request);
    public function getCategoryById($id);
    public function store(array $data);
    public function update(array $data, $id);
    public function delete($id);
}
