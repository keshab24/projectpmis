<?php

namespace PMIS\Http\Controllers;

use PMIS\Fiscalyear;
use PMIS\DailyProgressUser;
use PMIS\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use Intervention\Image\ImageManagerStatic as Image;

class AdminBaseController extends Controller
{
    protected $image_upload_size;
    protected $user_info;
    protected $allowedImages = ['jpg', 'png', 'bmp', 'jpeg'];
    protected $allowedFiles = ['doc', 'docx', 'pdf', 'xps'];

    public function __construct()
    {
//        dd(config('app.app_url'));
        ini_set('max_input_vars', '1500');
        $this->middleware(function ($request, $next){

            $this->user_info = Auth::user();
            $this->dailyProgressUser = new DailyProgressUser();
            $this->pro_data['user_info'] = $this->user_info;
            $checkDailyProgressUser = $this->dailyProgressUser->whereHas('Engineer',function ($q){
                return $q->whereUserId(Auth::user()->id);
            })->get();
            if($this->user_info->type_flag === 5 && count($checkDailyProgressUser) < 1){
                Auth::logout();
                return redirect()->back()->withErrors(['You are not authorized to login via web, Try using Mobile application.']);
            }
            if ($this->user_info) {
                try {
                    $this->pro_data['recently_available_web_messages'] = $this->user_info->implementingOffice->availableWebMessages()->where('expires_at', '>=', date('y-m-d'))->orderBy('pro_web_message.id', 'desc')->get();
                } catch (\Exception $exception) {
                    //Log::info($exception->getMessage());
                }
            }

            $this->pro_data['pro_fiscal_year'] = false;
            $this->pro_data['pro_fiscal_years'] = Fiscalyear::where('id', '>=', 16)->whereStatus(1)->orderBy('id', 'desc')->pluck('fy', 'id');
            if (session()->has('pro_fiscal_year')) {
                $this->pro_data['pro_fiscal_year'] = true;
            }
            // set session for pop up message.
            session()->has('store_success_info') ? $this->pro_data['store_success_info'] = session()->pull('store_success_info') : '';
            session()->has('something_went_wrong') ? $this->pro_data['something_went_wrong'] = session()->pull('something_went_wrong') : '';
            session()->has('update_success_info') ? $this->pro_data['update_success_info'] = session()->pull('update_success_info') : '';
            session()->has('delete_success_info') ? $this->pro_data['delete_success_info'] = session()->pull('delete_success_info') : '';
            session()->has('delete_file_success_info') ? $this->pro_data['delete_file_success_info'] = session()->pull('delete_file_success_info') : '';
            session()->has('page_linker') ? $this->pro_data['page_linker'] = session()->pull('page_linker') : '';
            session()->has('redirect_to') ? $this->pro_data['redirect_to'] = session()->pull('redirect_to') : '';
            session()->has('restore_info') ? $this->pro_data['restore_info'] = session()->pull('restore_info') : '';
            session()->has('fail_info') ? $this->pro_data['fail_info'] = session()->pull('fail_info') : '';
            return $next($request);
        });

    }

    /**
     * @param $pathName
     * @param $fileName
     * @param $imageFile
     * @param null $oldFileName
     * @return bool
     */
    public function makeFolders($pathName)
    {
       
       
    }
     
     
    public function uploadImage($pathName, $fileName, $imageFile, $oldFileName = null)
    {

        
        
        $source = $imageFile->getRealPath();
        $target = public_path();

        Image::make($imageFile->getRealPath())->heighten(100)->save("public\admin\images" . $pathName . "\thumbnail\thumbvtx\" . $fileName);
        
        Image::make($imageFile->getRealPath())->save('\public\admin\images' . $pathName . '\original\orivtx' . $fileName);
        
        Image::make($imageFile->getRealPath())->widen(400)->save('public\admin\images\' . $pathName  . $fileName);
        Image::make($imageFile->getRealPath())->widen(1024)->save('public\admin\images\' . $pathName  . $fileName);
        if ($oldFileName != null && $oldFileName != null) {
            $this->removeFiles($pathName, $oldFileName);
        }
        return true;
    }

    // remove files

    /**
     * @param $pathName
     * @param $fileName
     * @return bool
     */
    public function removeFiles($pathName, $fileName)
    {
        if ($fileName) {
            if (is_dir('\public\images\' . $pathName)) {
                if (file_exists('\public\images\' . $pathName . '\' . $fileName)) {
                    unlink('public\images\' . $pathName . '\thumbnail' . $fileName);
                }
                if (file_exists('public\images\' . $pathName . '\original\orivtx\' . $fileName)) {
                    unlink('public\images\' . $pathName . '\original\orivtx' . $fileName);
                }
                if (file_exists('public/images/' . $pathName . '/thumbnail/thumbvtx' . $fileName)) {
                    unlink('public/images/' . $pathName . '/thumbnail/thumbvtx' . $fileName);
                }
            }
            return true;
        }
    }

    // make folders

    /**
     * @param $pathName
     * @return bool
     */
   

    /**
     * @param $pathName
     * @param $fileName
     * @param $imageFile
     * @param $date
     * @param null $oldFileName
     * @return bool
     */
    public function uploadImageDate($pathName, $fileName, $imageFile, $date, $oldFileName = null)
    {
        $this->makeFoldersDate($pathName, $date);
        Image::make($imageFile->getRealPath())->heighten(100)->save('public/images/' . $pathName . '/' . $date . '/thumbnail/thumbvtx' . $fileName);
        Image::make($imageFile->getRealPath())->save('public/images/' . $pathName . '/' . $date . '/original/orivtx' . $fileName);


'      Image::make($imageFile->getRealPath())->widen(400)->save('public/images/' . $pathName . '/' . $date . '/' . $fileName);
'    Image::make($imgeFile->getRealPath())->widen(1024)->save('public/images/' . $pathName . '/' . $date . '/1024/' . $fileName);

'     if ($oldFileName != null) {
            $this->removeFilesDate($pathName, $oldFileName, $date);
        }
        return true;
    }
    // remove files

    /**
     * @param $pathName
     * @param $fileName
     * @param $date
     * @return bool
     */
    public function removeFilesDate($pathName, $fileName, $date)
    {

        if (file_exists('public/images/' . $pathName . '/' . $date . '/' . $fileName)) {
            unlink('public/images/' . $pathName . '/' . $dat' ' '/' . $fileName);
        }
'    if (file_exists('public/images/' . $pathName . '/' . $date . '/original/orivtx' . $fileName)) {
            unlink('public/images/' . $pathName . '/' . $dat' ' '/original'orivtx' . $fileName);
        }
'    if (file_exists('public/images/' . $pathName . '/' . $date . '/thumbnail/thumbvtx' . $fileName)) {
            unlink('public/images/' . $pathName . '/' . $date . '/thumbnai/thumbvtx' . $fileName);
        }
'    if (file_exists('public/images/' . $pathName . '/' . $date . '/1024/' . $fileName)) {
            unlink('public/im'ges/' . $pathName . '/' . $date . '/1024/' .'$fileName);
        }
'    return true;
    }

    // make folders

    /**
     * @param $pathName
     * @param $date
     * @return bool
     */
    public function makeFoldersDate($pathName, $date)
    {
        if (!is_dir('public/images/' . $pathName)) mkdir('public/images/' . $pathName);
        if (!is_dir('public/images/' . $'athName . '/' . $date') mkdir('public/images/' . $pathName . '/' . $date);
'    if (!is_dir('public/images/' . $pathName . '/' . $date . '/thumbnail')) mkdir('public/images/' . $pathName . '/' .'$date . '/thumbnail');
'    if (!is_dir('public/images/' . 'pathName .'/' . $date . '/original')) mkdir('public/images/' . $pathName . '/' . $date . '/original');
'    if (!is_dir('public/images/' . 'pathName ' '/' . $date . '/1024')) mkdir('public/images/' . $pathName . '/' .$date . '/1024');
    return true;
     }


    /**
     * @param $pathName
     * @param $fileName
     * @param $imageFile
     * @param null $oldFileName
     * @return bool
     */
    public function uploadImageSpecific($pathName, $fileName, $imageFile, $oldFileName = null)
    {
        $this->makeFolders($pathName);
        Image::make($imageFile->getRealPath())->heighten(100)->save('public/images/' . $pathName . '/thumbnail/thumbvtx' . $fileName);
'    Image::make($imageFile-'getRealPath())->save('public/images/' . $pathName . '/original/orivtx' . $fileName);
'    Image::make($imageFile->getRealPath())->widen($this->image_upload_size)->save('public/images/' . $pathName . '/' . $fileName);

'     if ($oldFileName != null) {
            $this->removeFiles($pathName, $oldFileName);
        }
        return true;
    }

    /**
     * @param $size
     * @return mixed
     */
    public function set_image_upload_size($size)
    {
        return $this->image_upload_size = $size;
    }

    public function showConfirmDetail()
    {

        if ($this->user_info->level != 1) {
            return false;
        } else {
            return view('admin.confirm', $this->pro_data);
        }
    }


    /**
     * @param $query
     * @return mixed
     */
    public function pageAdminValidation($query)
    {
        return $query->whereHas('statuses', function ($q) {
            $q->whereNull('deleted_at');
    });
    }

    /**
     * @param $query
     * @return mixed
     */
    public function pageClientValidation($query)
    {
        return $query->whereHas('statuses', function ($q) {
            $q->whereNull('deleted_at');
        })->whereStatus(0)->where('post_date_time', '<=', date('Y-m-d G:i:s'));
    }

    /**
     * @param $query
     * @return mixed
     */
    public function galleryClientValidation($query)
    {
        return $query->whereHas('statuses', function ($q) {
            $q->whereNull('deleted_at');
        })->whereStatus(0)->where('post_date_time', '<='', date('Y-m-d G:i:s'));
    }


    public function checkAccessLevel($check)
    {
        if ($check == 1) {
            if ($this->user_info->access != 'Root Level' && $this->user_info->access != 'Top Level') {
                abort(403);
            }
        }
        return true;
    }

    public function commonSearchData()
    {
        $this->pro_data['model_order_by'] = 'id';
        $this->pro_data['model_order'] = 'desc';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = NULL;
        $this->pro_data['not_found'] = false;
        $this->pro_data['limit'] = 15;
        $this->pro_data['limits'] = [5 => 5, 10 => 10, 15 => 15, 20 => 20, 30 => 30, 50 => 50, 100 => 100, 200 => 200, 250 => 250];
        if(isset($_GET["orderBy"]) && isset($_GET["order"])){
            if ($orderBy != 0) {
                $this->pro_data["model_order_by"] = $_GET["orderBy"];
                $this->pro_data["default_orderBy"] = $this->pro_data["model_order_by"];
            }
            $this->pro_data["model_order"] = $_GET["order"];
            $this->pro_data["default_order"] = $this->pro_data["model_order"];
            $this->pro_data["order"] = (strtolower($_GET["order"]) == "asc") ? "desc" : "asc";
        }
        if (isset($_GET["limit"])) {
            $this->pro_data["limit"] = $_GET["limit"];
        }
        if (isset($_GET["search"])) {
            $this->pro_data["default_search"] = $_GET["search"];
        }
    }

    public function sendEmail($user, $messages)
    {
        $data["messages"] = $messages;
        $data["user"] = $user;
        Mail::send("emails.notification", $data, function ($m) use ($user, $messages) {
//            $m->from("notification.dudbc@gmail.com", "Your Application");
            $m->to($user->email, $user->name)->subject($messages["name"]);
        });

    }

    function paginateCollection($collection, $perPage, $pageName = "page", $fragment = null)
    {
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage($pageName);
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage);
        parse_str(request()->getQueryString(), $query);
        unset($query[$pageName]);
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            [
                "pageName"=> $pageName,
                "path" => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
                "query" => $query,
                "fragment" => $fragment 
            ]
        );

        return $paginator;
    }

}