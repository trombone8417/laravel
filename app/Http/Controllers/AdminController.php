<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Tag;
use App\Category;
use App\User;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * 首頁驗證
     */
    public function index(Request $request)
    {
        // 確認是否登入且受否為login頁面
        // 未登入轉到login頁面
        // 已經為login頁面的話停止執行避免迴圈
        if (!Auth::check() && $request->path() != 'login') {
            return redirect('/login');
        }
        // 未登入且在login頁面，顯示歡迎頁面
        if (!Auth::check() && $request->path() == 'login') {

            return view('welcome');
        }
        $user = Auth::user();
        // 已登入且角色為User
        if ($user->userType == 'User') {
            return redirect('/login');
        }
        // 若登入為其他使用者，轉到Home頁面
        if ($request->path() == 'login') {
            return redirect('/');
        }

        return $this->checkForPermission($user, $request);
    }

    /**
     * 確認使用者權限
     */
    public function checkForPermission($user, $request)
    {
        // 將json轉成陣列或object
        $permission = json_decode($user->role->permission);
        $hasPermission = false;
        // 使用者是否有讀取權限，由role判斷
        foreach ($permission as $p) {
            if ($p->name == $request->path()) {
                // 若有讀取權限
                if ($p->read) {
                    $hasPermission = true;
                }
            }
        }
        // 跳至首頁
        if ($hasPermission) return view('welcome');
        // 轉至權限不足頁面
        return view('notfound');
    }

    /**
     * 登出
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    /**
     * 新增Tag
     *
     * @param Request $request
     * @return void
     */
    public function addTag(Request $request)
    {
        // 驗證請求資料
        $this->validate($request, [
            'tagName' => 'required'
        ]);
        // 新增Tag
        return Tag::create([
            'tagName' => $request->tagName
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
        $this->validate($request, [
            'tagName' => 'required',
            'id' => 'required',
        ]);
        // 更新Tag
        return Tag::where('id', $request->id)->update([
            'tagName' => $request->tagName
        ]);
    }

    /**
     * 刪除Tag
     */
    public function deleteTag(Request $request)
    {
        // 驗證請求資料
        $this->validate($request, [
            'id' => 'required',
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
        return Tag::orderBy('id', 'desc')->get();
    }
    // 上傳圖片
    public function upload(Request $request)
    {
        // 後端驗證請求資料
        $this->validate($request, [
            'file' => 'required|mimes:jpg,jpeg,png'
        ]);
        // 命名，例如：1608603484.png
        $picName = time() . '.' . $request->file->extension();
        // 圖片放置地點
        $request->file->move(public_path('uploads'), $picName);
        return $picName;
    }
    /**
     * vue-editor-js 上傳圖片
     */
    public function uploadEditorImage(Request $request)
    {
        // 後端驗證請求資料
        $this->validate($request, [
            'image' => 'required|mimes:jpg,jpeg,png'
        ]);
        // 命名，例如：1608603484.png
        $picName = time() . '.' . $request->image->extension();
        // 圖片放置地點
        $request->image->move(public_path('uploads'), $picName);
        return response()->json([
            'success' => 1,
            'file' => [
                'url' => "http://127.0.0.1:8000/uploads/$picName"
            ]
        ]);
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
            $filePath = public_path() . $fileName;
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
        $this->validate($request, [
            'iconImage' => 'required',
            'categoryName' => 'required',
        ]);
        // 新增Category
        return Category::create([
            'iconImage' => $request->iconImage,
            'categoryName' => $request->categoryName,
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
        $this->validate($request, [
            'iconImage' => 'required',
            'categoryName' => 'required',
        ]);
        // 更新Category
        return Category::where('id', $request->id)->update([
            'iconImage' => $request->iconImage,
            'categoryName' => $request->categoryName,
        ]);
    }
    /**
     * 刪除Category
     */
    public function deleteCategory(Request $request)
    {
        // 刪除uploads的圖片
        $this->deleteFileFromServer($request->iconImage);
        // 驗證請求資料
        $this->validate($request, [
            'id' => 'required',
        ]);
        // 刪除Category
        return Category::where('id', $request->id)->delete();
    }

    public function createUser(Request $request)
    {
        // bail：一個屬性首次驗證失敗時，停止此屬性的其他驗證規則。
        $this->validate($request, [
            // 全名
            'fullName' => 'required',
            // Email不能重複，須符合email格式
            'email' => 'bail|required|email|unique:users',
            // 密碼不得少於六個字元
            'password' => 'bail|required|min:6',
            // 使用者角色
            'role_id' => 'required',
        ]);
        // 密碼加密
        $password = bcrypt($request->password);
        $user = User::create([
            'fullName' => $request->fullName,
            // 加密過後的密碼
            'email' => $request->email,
            'password' => $password,
            'role_id' => $request->userType,
        ]);
        return $user;
    }

    public function editUser(Request $request)
    {
        $this->validate($request, [
            // 全名
            'fullName' => 'required',
            // Email不能重複，須符合email格式
            'email' => "bail|required|email|unique:users,email,$request->id",
            // 密碼不得少於六個字元
            'password' => 'min:6',
            // 使用者角色
            'role_id' => 'required',
        ]);
        // 密碼加密
        $data = [
            'fullName' => $request->fullName,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ];
        if ($request->password) {
            // 加密過後的密碼
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }
        $user = User::where('id', $request->id)->update($data);

        return $user;
    }
    public function deleteUser(Request $request)
    {
        // 驗證請求資料
        $this->validate($request, [
            'id' => 'required',
        ]);
        // 刪除User
        return User::where('id', $request->id)->delete();
    }
    // 撈出特權帳號使用者
    public function getUsers()
    {
        return User::get();
    }

    public function adminLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6',
        ]);


        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // 確認使用者是否登入且角色為User
            $user = Auth::user();
            Log::info($user->role);
            if ($user->role->isAdmin == 0) {
                // 若角色是User的話，登出
                Auth::logout();
                // 401 Unauthorized
                return response()->json([
                    'msg' => '使用者角色錯誤',
                ], 401);
            }
            return response()->json([
                'msg' => '登入成功',
            ]);
        } else {
            // 401 Unauthorized
            return response()->json([
                'msg' => '登入失敗',
            ], 401);
        }
    }
    /**
     * 新增角色
     */
    public function createRole(Request $request)
    {
        $this->validate($request, [
            'roleName' => 'required',
        ]);
        return Role::create([
            'roleName' => $request->roleName
        ]);
    }
    public function editRole(Request $request)
    {
        $this->validate($request, [
            'roleName' => 'required',
        ]);
        return Role::where('id', $request->id)->update([
            'roleName' => $request->roleName
        ]);
    }
    public function deleteRole(Request $request)
    {
        // 驗證請求資料
        $this->validate($request, [
            'id' => 'required',
        ]);
        // 刪除User
        return Role::where('id', $request->id)->delete();
    }


    /**
     * 撈出所有使用者角色
     */
    public function getRoles()
    {
        return Role::all();
    }
    public function assignRole(Request $request)
    {
        $this->validate($request, [
            'permission' => 'required',
            'id' => 'required',
        ]);
        return Role::where('id', $request->id)->update([
            'permission' =>  $request->permission
        ]);
    }
    /**
     * 判斷標題是否重複
     */
    public function slug()
    {
        $title = 'This is a nice title test2';
        return Blog::create([

            'title' => $title ,
            'post' => 'some post',
            'post_excerpt' => 'some post_excerpt' ,
            'user_id' => 1 ,
            'metaDescription' => 'some metaDescription' ,

        ]);
        return $title;
    }
    /**
     * 新增部落格文章
     */
    public function createBlog(Request $request)
    {
        return Blog::create([

            'title' => $request->title ,
            'post' => $request->post,
            'post_excerpt' => $request->post_excerpt ,
            'user_id' => Auth::user()->id ,
            'metaDescription' => $request->metaDescription,
            'jsonData' => $request->jsonData,

        ]);
    }
}
