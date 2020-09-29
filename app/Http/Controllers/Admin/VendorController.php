<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\Vendor;

class VendorController extends AdminBaseController {
    protected $pro_data;
    protected $vendor;
    public function __construct(Vendor $vendor){
        parent::__construct();
        $this->middleware(function ($request, $next) use ($vendor) {
            restrictEngineers($this->user_info->type_flag);
            $this->vendor = $vendor;
            return $next($request);
        });
    }
    public function index(){
        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        if(isset($_GET['search'])){
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['vendors'] = $this->vendor->search($_GET['search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['vendors']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }else{
            $this->pro_data['vendors'] = $this->vendor;
        }
        if(isset($_GET['orderBy']) && isset($_GET['order'])){
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order'])=='asc')?'desc':'asc';
        }
        if(isset($_GET['trashes'])){
            $this->pro_data['vendors'] = $this->pro_data['vendors']->onlyTrashed();
        }
        $this->pro_data['trashes_no'] = $this->vendor->onlyTrashed()->count();
        $this->pro_data['vendors'] = $this->pro_data['vendors']->orderBy($this->pro_data['orderBy'],$this->pro_data['order'])->paginate(10);
        return view('admin.vendor.index',$this->pro_data);
    }

    public function create(){
        return view('admin.vendor.create', $this->pro_data);
    }

    public function store(Request $request){
        $status = $request->get('status') == 'on'?1:0;
        $image = $request->file('image');
        $imageName = '';
        if($image != null && $image != '' && !empty($image) && isset($image)){
            $imageName = makeImageName($image->getClientOriginalName());
            $this->uploadImage('vendor', $imageName, $image);
        }
        $filesToUpload = '';
        foreach($request->file('files') as $file){
            if($file){
                $files[] = $fileName = getFileName($file);
                $file->move('public/vendorFiles',$fileName);
            }
        }
        if(isset($files)){
            $filesToUpload = implode(',',$files);
        }
        $this->vendor->create([
            'name'=>$request->get('name'),
            'vat_no'=>$request->get('vat_no'),
            'address'=>$request->get('address'),
            'contact'=>$request->get('contact'),
            'image'=>$imageName,
            'file'=> $filesToUpload,
            'description'=>$request->get('description'),
            'created_by'=> Auth::user()->id,
            'updated_by'=> Auth::user()->id,
            'status'=>$status
        ]);
        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'Vendor');

        session()->flash('store_success_info','" vendor name '.$request->get('name').'"');
        return redirect()->route('vendor.create');
    }

    public function show(){

    }

    public function edit(Vendor $vendor){
        $this->pro_data['vendor'] = $vendor;
        return view('admin.vendor.edit', $this->pro_data);
    }

    public function update(Request $request, Vendor $vendor){
        $status = $request->get('status') == 'on'?1:0;
        $image = $request->file('image');
        $imageName = $vendor->image;
       if($image != null && $image != '' && !empty($image) && isset($image)){
            $imageName = makeImageName($image->getClientOriginalName());
            $this->uploadImage('vendor', $imageName, $image);
        }

        $oldImages = $request->get('oldImages');
        $deleteMe = $request->get('deleteMe');
        $filesUploaded = '';
        foreach($oldImages as $index => $image){
            if($image != 0 ){
                if($deleteMe[$index] == 0)
                    if($filesUploaded == '')
                        $filesUploaded.=$image;
                    else
                        $filesUploaded.=",".$image;
                else{
                    if(file_exists(asset('public/vendorFiles/'.$image)))
                        unlink(asset('public/vendorFiles/'.$image));
                }

            }
        }
        foreach($request->file('files') as $file){
            if($file){
                $files[] = $fileName = getFileName($file);
                $file->move('public/vendorFiles',$fileName);
            }
        }
        if(isset($files)){
            if($filesUploaded == '')
                $filesUploaded.=implode(',',$files);
            else
                $filesUploaded.=",".implode(',',$files);
        }
        $oldVendor=$vendor->toArray();
        $vendor->fill([
            'name'=>$request->get('name'),
            'vat_no'=>$request->get('vat_no'),
            'address'=>$request->get('address'),
            'contact'=>$request->get('contact'),
            'image'=>$imageName,
            'file'=> $filesUploaded,
            'description'=>$request->get('description'),
            'updated_by'=> Auth::user()->id,
            'status'=>$status
        ])->save();
        $change=logDescriptionUpdate($vendor, $oldVendor);
        if($change!=false){
            storeLog(null,$change,1 ,'Vendor');
        }

        session()->flash('update_success_info','" vendor named '.$request->get('name').'"');
        return redirect()->route('vendor.index');
    }

    public function destroy(Vendor $vendor){
        $name = $vendor->name;
        if(Input::get('hardDelete')){
            $vendor->forceDelete();
        }else{
            $vendor->delete();
        }
        storeLog(null,$name,2 ,'Vendor');

        session()->flash('delete_success_info','" vendor named '.$name.'"');
        return redirect()->route('vendor.index');
    }
}
