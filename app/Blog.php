<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = ['title','post','post_excerpt','slug','user_id','featuredImage','metaDescription','views'];

    public function setTitleAttribute($title){
        $this->attributes['slug'] = $this->uniqueSlug($title);
    }
    // 若標題重複的話，後面編碼
    private function uniqueSlug($title){
        $slug = Str::slug($title,'-');
        $count = Blog::where('slug', 'LIKE', "{$slug}%")->count();
        $newCount = $count > 0 ? ++$count : '';
        return $newCount > 0 ? "$slug-$newCount" : $slug;
    }
}
