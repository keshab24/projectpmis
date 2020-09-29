<?php

namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\Material;

class MaterialController extends AdminBaseController
{
    public $material;
    public function __construct(Material $material){
        parent::__construct();
        $this->middleware(function ($request, $next) use ($material) {
            $this->material = $material;
            if($this->user_info->implementing_office_id !== 410){
                return abort(404);
            }
            restrictEngineers($this->user_info->type_flag);
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->pro_data['materials'] = $this->material;
        $this->commonSearchData();
        if (request()->has('search')){
            $this->pro_data['materials'] = $this->pro_data['materials']->search($this->pro_data['default_search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['materials']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }
        $this->pro_data['materials'] = $this->paginateCollection($this->pro_data['materials']->get(), $this->pro_data['limit']);
        return view('admin.material.index', $this->pro_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.material.create', $this->pro_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $material = new Material($request->all());
        $order = Material::max('order');
        $material->fill([
            'status' => $request->get('status') == 'on' ? 1 : 0,
            'order' => $order ? $order + 1 : 1,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id()
        ])->save();
        session()->flash('store_success_info', '" Material stored successfully."');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Material $material)
    {
        return view('admin.material.show', $this->pro_data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Material $material)
    {
        $this->pro_data['material'] = $material;
        return view('admin.material.edit', $this->pro_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Material $material)
    {
        $material = $material->fill($request->all());
        $material->fill([
            'status' => $request->get('status') == 'on' ? 1 : 0,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id()
        ])->save();
        session()->flash('update_success_info', '" Material updated successfully."');
        return redirect()->route('material.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Material $material)
    {
        if(Input::get('hardDelete')){
            $material->forceDelete();
        }else{
            $material->delete();
        }
        session()->flash('delete_success_info','" material deleted "');
        return redirect()->route('month.index');
    }
}
