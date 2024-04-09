<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostModel extends Model
{
    use HasFactory;

    protected $table = "posts";

    public function getAllPosts()
    {
        return $this::latest()->paginate(1);
    }

    public function postCheck($postTitle)
    {
        return $this::where('post_title', $postTitle)->first();
    }

    public function getPostById($id)
    {
        return $this::find($id);
    }

    public function postCheckCategory($id)
    {
        return $this::where('category_id', $id)->get();
    }

    public function postSearch($keyword)
    {
        return $this::where('post_title', 'like', '%' . $keyword . '%')
            ->orWhere('post_content', 'like', '%' . $keyword . '%')
            ->paginate(1);
    }

    public function getPostTopFocus()
    {
        return $this::select('id', 'post_title', 'post_slug', 'post_image')->take(9)->get();
    }

    public function getLatestPosts()
    {
        return $this::select('posts.id', 'posts.post_title', 'posts.post_slug', 'posts.post_image', 'posts.created_at', 'categories.category_name')
            ->join('categories', 'posts.category_id', '=', 'categories.id')
            ->latest()->limit(21)
            ->get();
    }

    public function getPostWithCategory($id, $number)
    {
        return $this::select('id', 'post_title', 'post_slug', 'post_image', 'created_at')
            ->where('category_id', '=', $id)
            ->take($number)->get();
    }

    public function getLatestPostTable()
    {
        return $this::select('posts.id', 'posts.post_title', 'posts.post_slug', 'posts.created_at')
            ->join('categories', 'posts.category_id', '=', 'categories.id')
            ->latest()->limit(10)
            ->get();
    }

    public function getPostFocus()
    {
        return $this::select('id', 'post_title', 'post_slug', 'post_image')->take(5)->get();
    }

    public function getPostTopFocusByCategorySlug($slug)
    {
        return $this::select('posts.id', 'posts.post_title', 'posts.post_slug', 'posts.post_image')
            ->join('categories', 'posts.category_id', '=', 'categories.id')
            ->where('categories.category_slug', '=', $slug)
            ->where('category_slug', '=', $slug)
            ->take(9)->get();
    }

    public function getLatestPostByCategorySlug($slug)
    {
        return $this::select('posts.id', 'posts.post_title', 'posts.post_slug', 'posts.post_image', 'posts.created_at', 'categories.category_name')
            ->join('categories', 'posts.category_id', '=', 'categories.id')
            ->where('categories.category_slug', '=', $slug)
            ->latest('posts.created_at')->limit(9)
            ->get();
    }

    public function getPostBySlug($slug)
    {
        return $this::select('id', 'post_title', 'post_slug', 'post_content')
            ->where('post_slug', '=', $slug)
            ->first();
    }
}
