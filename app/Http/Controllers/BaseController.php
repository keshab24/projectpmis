<?php
namespace PMIS\Http\Controllers;

use Intervention\Image\ImageManagerStatic as Image;

class BaseController extends Controller
{

    public function __construct()
    {
        // set session for pop up message.
        session()->has('store_success_info') ? $this->pro_data['store_success_info'] = session()->pull('store_success_info') : '';
        session()->has('update_success_info') ? $this->pro_data['update_success_info'] = session()->pull('update_success_info') : '';
        session()->has('delete_success_info') ? $this->pro_data['delete_success_info'] = session()->pull('delete_success_info') : '';
        session()->has('delete_file_success_info') ? $this->pro_data['delete_file_success_info'] = session()->pull('delete_file_success_info') : '';
        session()->has('page_linker') ? $this->pro_data['page_linker'] = session()->pull('page_linker') : '';
        session()->has('redirect_to') ? $this->pro_data['redirect_to'] = session()->pull('redirect_to') : '';
        session()->has('restore_info') ? $this->pro_data['restore_info'] = session()->pull('restore_info') : '';
        //
    }

    public function pro_formatted_url($url)
    {
        return preg_replace("/www./", '', $url);
    }

    /**
     * @param $pathName
     * @param $fileName
     * @param $imageFile
     * @param null $oldFileName
     * @return bool
     */
    public function uploadImage($pathName, $fileName, $imageFile, $oldFileName = null)
    {
        $this->makeFolders($pathName);
        Image::make($imageFile->getRealPath())->heighten(100)->save('public/images/' . $pathName . '/thumbnail/thumbvtx' . $fileName);
        Image::make($imageFile->getRealPath())->save('public/images/' . $pathName . '/original/orivtx' . $fileName);
        Image::make($imageFile->getRealPath())->widen(400)->save('public/images/' . $pathName . '/' . $fileName);
        if ($oldFileName != null && $oldFileName != ' ') {
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

        if (file_exists('public/images/' . $pathName . '/' . $fileName)) {
            unlink('public/images/' . $pathName . '/' . $fileName);
        }
        if (file_exists('public/images/' . $pathName . '/original/orivtx' . $fileName)) {
            unlink('public/images/' . $pathName . '/original/orivtx' . $fileName);
        }
        if (file_exists('public/images/' . $pathName . '/thumbnail/thumbvtx' . $fileName)) {
            unlink('public/images/' . $pathName . '/thumbnail/thumbvtx' . $fileName);
        }
        return true;
    }

    // make folders
    /**
     * @param $pathName
     * @return bool
     */
    public function makeFolders($pathName)
    {
        if (!is_dir('public/images/' . $pathName)) mkdir('public/images/' . $pathName);
        if (!is_dir('public/images/' . $pathName . '/thumbnail')) mkdir('public/images/' . $pathName . '/thumbnail');
        if (!is_dir('public/images/' . $pathName . '/original')) mkdir('public/images/' . $pathName . '/original');
        return true;
    }

    public function send_email($client, $selector, $extra_info = null)
    {
        $mail_data['client_info'] = $client;
        $mail_data['website_info'] = $client->website;
        return true;
    }
}
