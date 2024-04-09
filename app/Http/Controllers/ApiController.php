<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Resources\Category;
use App\Models\CategoryModel;
use App\Models\PostModel;

class ApiController extends Controller
{
    public function index()
    {
        $category = new CategoryModel();
        $categories = $category->getAllCategories();

        if ($categories) {
            return response()->json([
                'status' => 201,
                'message' => 'ok',
                'data' => $categories
            ]);
        }

        return response()->json([
            'status' => 404,
            'message' => 'data not found',
            'data' => null
        ]);
    }

    public function categoryId($id)
    {
        $category = new CategoryModel();
        $categories = $category->getCategoryById($id);

        if ($categories) {
            return response()->json([
                'status' => 201,
                'message' => 'ok',
                'data' => $categories
            ]);
        }

        return response()->json([
            'status' => 404,
            'message' => 'data not found',
            'data' => null
        ]);
    }

    public function addCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categoryName' => 'required|min:3|max:20',
        ], [
            'categoryName.required' => 'You have not entered your category name',
            'categoryName.min' => 'Category name must be at least 3 characters long',
            'categoryName.max' => 'category name must be no longer than 20 characters',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'message' => 'Thêm mới không thành công',
                'data' => $validator->errors()
            ]);
        }

        $category = new CategoryModel();
        $checkCategory = $category->categoryCheck($request->categoryName);

        if (!$checkCategory) {
            $newCategory = new CategoryModel();

            $newCategory->category_name = $request->categoryName;
            $newCategory->category_slug = Str::slug($request->categoryName);
            $newCategory->category_status = $request->categoryStatus;
            $newCategory->user_id = '1';

            $newCategory->save();
            return response()->json([
                'status' => 201,
                'message' => 'Thêm mới thành công',
                'data' => null
            ]);
        }

        return response()->json([
            'status' => 404,
            'message' => 'Thêm mới không thành công',
            'data' => null
        ]);
    }

    public function deleteCategory($id)
    {
        $category = new CategoryModel();
        $categoryItem = $category->getCategoryById($id);

        $post = new PostModel();
        $postCheck = $post->postCheckCategory($id);

        if (isset($postCheck) && count($postCheck) > 0) {
            return response()->json([
                'status' => 404,
                'message' => 'Bạn chưa xóa bài viết chứa danh mục',
                'data' => null
            ]);
        } else {
            $categoryItem->delete();
            return response()->json([
                'status' => 201,
                'message' => 'Xóa thành công',
                'data' => null
            ]);
        }
    }
}
