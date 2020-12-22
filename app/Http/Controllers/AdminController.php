<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * 新增Tag
     *
     * @param Request $request
     * @return void
     */
    public function addTag(Request $request)
    {
        // 驗證請求資料
        $this->validate($request,[
            'tagName'=>'required'
        ]);
        return Tag::create([
            'tagName'=> $request->tagName
        ]);
    }

    /**
     * 編輯Tag
     *
     * @param Request $request
     * @return void
     */
    public function editTag(Request $request)
    {
        // 驗證請求資料
        $this->validate($request,[
            'tagName'=>'required',
            'id'=>'required',
        ]);
        return Tag::where('id', $request->id)->update([
            'tagName'=> $request->tagName
        ]);

    }

    public function deleteTag(Request $request)
    {
        // 驗證請求資料
        $this->validate($request,[
            'id'=>'required',
        ]);
        return Tag::where('id', $request->id)->delete();
    }
    /**
     * 取得資料庫Tag資料
     *
     * @return void
     */
    public function getTag()
    {
        return Tag::orderBy('id','desc')->get();
    }
}
