<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Category;
use App\User;
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
        // 新增Tag
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
        // 更新Tag
        return Tag::where('id', $request->id)->update([
            'tagName'=> $request->tagName
        ]);

    }

    /**
     * 刪除Tag
     */
    public function deleteTag(Request $request)
    {
        // 驗證請求資料
        $this->validate($request,[
            'id'=>'required',
        ]);
        // 刪除Tag
        return Tag::where('id', $request->id)->delete();
    }
    /**
     * 取得資料庫Tag資料
     *
     * @return void
     */
    public function getTag()
    {
        // id由大到小排序
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
     * 刪除圖片
     *
     * @param Request $request
     * @return void
     */
    public function deleteImage(Request $request)
    {
        // 取得照片位置
        $fileName = $request->imageName;
        // 呼叫刪除文件
        $this->deleteFileFromServer($fileName, false);
        return 'done';
    }
    /**
     * 刪除文件
     *
     * @param [type] $fileName
     * @return void
     */
    public function deleteFileFromServer($fileName, $hasFullPath = false)
    {
        if (!$hasFullPath) {
            // 照片絕對位置
            $filePath = public_path().$fileName;
        }
        // 若照片存在的話
        if (file_exists($filePath)) {
            // 刪除文件
            @unlink($filePath);
        }
        return;
    }
    /**
     * 新增 Category
     *
     * @param Request $request
     * @return void
     */
    public function addCategory(Request $request)
    {
        // 驗證請求資料
        $this->validate($request,[
            'iconImage'=>'required',
            'categoryName'=>'required',
        ]);
        // 新增Category
        return Category::create([
            'iconImage'=> $request->iconImage,
            'categoryName'=> $request->categoryName,
        ]);
    }
    /**
     * 資料庫撈取Category資料
     *
     * @return void
     */
    public function getCategory()
    {
        // id由大到小排序
        return Category::orderBy('id', 'desc')->get();
    }
    /**
     * 編輯Category
     */
    public function editCategory(Request $request)
    {
        // 驗證請求資料
        $this->validate($request,[
            'iconImage'=>'required',
            'categoryName'=>'required',
        ]);
        // 更新Category
        return Category::where('id', $request->id)->update([
            'iconImage'=> $request->iconImage,
            'categoryName'=> $request->categoryName,
        ]);

    }
    /**
     * 刪除Category
     */
    public function deleteCategory(Request $request){
        // 刪除uploads的圖片
        $this->deleteFileFromServer($request->iconImage);
        // 驗證請求資料
        $this->validate($request,[
            'id'=>'required',
        ]);
        // 刪除Category
        return Category::where('id', $request->id)->delete();
    }

    public function createUser(Request $request)
    {
        $this->validate($request,[
            'fullName'=>'required',
            'email'=>'bail|required|email',
            'password'=>'bail|required|min:6',
            'userType'=>'required',
        ]);
        $password = bcrypt($request->password);
        $user = User::create([
            'fullName'=>$request->fullName,
            'email'=>$request->email,
            'password'=>$password,
            'userType'=>$request->userType,
        ]);
        return $user;
    }
    public function getUsers(){
        return User::where('userType','!=','User')->get();
    }
}
