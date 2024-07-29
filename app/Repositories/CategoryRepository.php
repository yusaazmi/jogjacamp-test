<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Models\User;
use App\Notifications\CategoryNotification;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAllCategories(Request $request)
    {
        $query = Category::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $limit = (int) $request->input('limit', 10);
        $page = (int) $request->input('page', 1);

        $categories = $query->paginate($limit, ['*'], 'page', $page);

        return $categories;
    }
    public function store(array $data)
    {
        $category = new Category();
        $category->name = $data['name'];
        $category->is_publish = $data['is_publish'];
        $category->save();

        $users = User::all();
        foreach ($users as $user) {
            $user->notify(new CategoryNotification('created', $category->name));
        }

        return $category;
    }

    public function getCategoryById($id)
    {
        return Category::findOrFail($id);
    }

    public function update(array $data, $id)
    {
        $category = Category::findOrFail($id);
        $category->name = $data['name'];
        $category->is_publish = $data['is_publish'];
        $category->save();

        $users = User::all();
        foreach ($users as $user) {
            $user->notify(new CategoryNotification('updated', $category->name));
        }

        return $category;
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        $users = User::all();
        foreach ($users as $user) {
            $user->notify(new CategoryNotification('deleted', $category->name));
        }

        return $category;
    }
}