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
    // 上傳圖片
    public function upload(Request $request)
    {
        // 後端驗證請求資料
        $this->validate($request,[
            'file' => 'required|mimes:jpg,jpeg,png'
        ]);
        // 命名，例如：1608603484.png
        $picName = time().'.'.$request->file->extension();
        // 圖片放置地點
        $request->file->move(public_path('uploads'),$picName);
        return $picName;
    }
    /**
     *
     *
     * @param Request $request
     * @return void
     */
    public function deleteImage(Request $request)
    {
        $fileName = $request->imageName;
        $this->deleteFileFromServer($fileName);
        return 'done';
    }
    /**
     * 刪除文件
     *
     * @param [type] $fileName
     * @return void
     */
    public function deleteFileFromServer($fileName)
    {
        $filePath = public_path().'/uploads/'.$fileName;
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
        return;
    }
}
