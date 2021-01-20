<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Category;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::select('id','categoryName')->limit(10)->get();
        $blogs = Blog::orderBy('id', 'desc')->with(['cat', 'user'])->limit(6)->get(['id','title','post_excerpt','slug','user_id','featuredImage']);

        return view('home')->with(['categories'=>$categories, 'blogs'=>$blogs]);
    }
}
