<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    use HasFactory;

    protected $table = "categories";

    public function getAllCategories()
    {
        // return $this::latest()->paginate(1);
        return $this::all();
    }

    public function categoryCheck($categoryName)
    {
        return $this::where('category_name', $categoryName)->first();
    }

    public function getCategoryById($id)
    {
        return $this::find($id);
    }

    public function categoryCheckUser($id)
    {
        return $this::where('user_id', $id)->get();
    }

    public function categorySearch($keyword)
    {
        return $this::where('category_name', 'like', '%' . $keyword . '%')
            ->paginate(1);
    }

    public function getCategoryNames()
    {
        return $this::where('category_status', 1)->select('id', 'category_name')->get();
    }

    public function getCategoryList()
    {
        return $this::where('category_status', 1)->get();
    }
}
