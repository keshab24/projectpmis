<?php

namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\Equipment;

class EquipmentController extends AdminBaseController
{
    public $equipment;
    public function __construct(Equipment $equipment){
        parent::__construct();
        $this->middleware(function ($request, $next) use ($equipment) {
            $this->equipment = $equipment;

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
        $this->pro_data['equipments'] = $this->equipment;
        $this->commonSearchData();
        if (request()->has('search')){
            $this->pro_data['equipments'] = $this->pro_data['equipments']->search($this->pro_data['default_search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['equipments']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }
        $this->pro_data['equipments'] = $this->paginateCollection($this->pro_data['equipments']->get(), $this->pro_data['limit']);
        return view('admin.equipment.index', $this->pro_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.equipment.create', $this->pro_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $equipment = new Equipment($request->all());
        $order = Equipment::max('order');
        $equipment->fill([
            'status' => $request->get('status') == 'on' ? 1 : 0,
            'order' => $order ? $order + 1 : 1,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id()
        ])->save();
        session()->flash('store_success_info', '" Equipment stored successfully."');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Equipment $equipment)
    {
        return view('admin.equipment.show', $this->pro_data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Equipment $equipment)
    {
        $this->pro_data['equipment'] = $equipment;
        return view('admin.equipment.edit', $this->pro_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Equipment $equipment)
    {
        $equipment = $equipment->fill($request->all());
        $equipment->fill([
            'status' => $request->get('status') == 'on' ? 1 : 0,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id()
        ])->save();
        session()->flash('update_success_info', '" Equipment updated successfully."');
        return redirect()->route('equipment.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Equipment $equipment)
    {
        if(Input::get('hardDelete')){
            $equipment->forceDelete();
        }else{
            $equipment->delete();
        }
        session()->flash('delete_success_info','" equipment deleted "');
        return redirect()->route('month.index');
    }
}
