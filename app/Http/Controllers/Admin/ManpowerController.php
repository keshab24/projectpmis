<?php

namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\Http\Controllers\Controller;
use PMIS\Manpower;
use Illuminate\Support\Facades\Input;

class ManpowerController extends AdminBaseController
{
    public $manpower;
    public function __construct(Manpower $manpower){
        parent::__construct();
        $this->middleware(function ($request, $next) use ($manpower) {
//            dd('hakdfhahdfjahfjhad');
            $this->manpower = $manpower;
            $this->pro_data['categories'] = $this->categories();
//            if($this->user_info->implementing_office_id !== 410){
//                return abort(404);
//            }
//            restrictEngineers($this->user_info->type_flag);
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
        $this->pro_data['manpowers'] = $this->manpower;
        $this->commonSearchData();
        if (request()->has('search')){
            $this->pro_data['manpowers'] = $this->pro_data['manpowers']->search($this->pro_data['default_search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['manpowers']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }

        if(isset($_GET['orderBy']) && isset($_GET['order'])){
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order'])=='asc')?'desc':'asc';
            $this->pro_data['manpowers'] = $this->pro_data['manpowers']->orderBy($this->pro_data['orderBy'],$this->pro_data['order']);
        }

        if (request()->has('type') && request()->get('type'))
            $this->pro_data['manpowers'] = $this->pro_data['manpowers']->where('type', request()->get('type'));


        $this->pro_data['manpowers'] = $this->paginateCollection($this->pro_data['manpowers']->get(), $this->pro_data['limit']);
        return view('admin.manpower.index', $this->pro_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.manpower.create', $this->pro_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $manpower = new Manpower($request->all());
        $order = Manpower::max('order');
        $manpower->fill([
            'countable' => $request->get('countable') == 'on' ? 1 : 0,
            'status' => $request->get('status') == 'on' ? 1 : 0,
            'order' => $order ? $order + 1 : 1,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id()
        ])->save();
        session()->flash('store_success_info', '" Manpower stored successfully."');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Manpower $manpower)
    {
        return view('admin.manpower.show', $this->pro_data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Manpower $manpower)
    {
        $this->pro_data['manpower'] = $manpower;
        return view('admin.manpower.edit', $this->pro_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Manpower $manpower)
    {
        $manpower = $manpower->fill($request->all());
        $manpower->fill([
            'countable' => $request->get('countable') == 'on' ? 1 : 0,
            'status' => $request->get('status') == 'on' ? 1 : 0,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id()
        ])->save();
        session()->flash('update_success_info', '" Manpower updated successfully."');
        return redirect()->route('manpower.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Manpower $manpower
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function manpowerDestroy(Manpower $manpower)
    {
        if(Input::get('hardDelete')){
            $manpower->forceDelete();
        }else{
            $manpower->delete();
        }
        session()->flash('delete_success_info','" manpower deleted "');
        return redirect()->route('month.index');
    }

    public function categories()
    {
        try{
            $categories = $this->manpower->pluck('categories')->toArray();
            $category_arr = [];

            foreach(array_values(array_filter($categories)) as $category){
                if(is_array($category)){
                    foreach($category as $cat){
                        $category_arr[$cat] = $cat;
                    }
                }else{
                    $category_arr[$category] = $category;
                }
            }
        }catch (\Exception $exception){
            return [];
        }

        return $category_arr;
    }
}
