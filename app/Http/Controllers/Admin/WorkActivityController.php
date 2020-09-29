<?php

namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\WorkActivity;

class WorkActivityController extends AdminBaseController
{
    public $work_activity;
    public function __construct(WorkActivity $work_activity){
        parent::__construct();

        $this->middleware(function ($request, $next) use ($work_activity) {
            $this->work_activity = $work_activity;
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
        $this->pro_data['work_activities'] = $this->work_activity;
        $this->commonSearchData();
        if (request()->has('search')){
            $this->pro_data['work_activities'] = $this->pro_data['work_activities']->search($this->pro_data['default_search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['work_activities']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }
        $this->pro_data['work_activities'] = $this->paginateCollection($this->pro_data['work_activities']->get(), $this->pro_data['limit']);
        return view('admin.work-activity.index', $this->pro_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.work-activity.create', $this->pro_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $work_activity = new WorkActivity($request->all());
        $order = WorkActivity::max('order');
        $work_activity->fill([
            'status' => $request->get('status') == 'on' ? 1 : 0,
            'order' => $order ? $order + 1 : 1,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id()
        ])->save();
        session()->flash('store_success_info', '" WorkActivity stored successfully."');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(WorkActivity $work_activity)
    {
        return view('admin.work-activity.show', $this->pro_data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkActivity $work_activity)
    {
        $this->pro_data['material'] = $work_activity;
        return view('admin.work-activity.edit', $this->pro_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkActivity $work_activity)
    {
        $work_activity = $work_activity->fill($request->all());
        $work_activity->fill([
            'status' => $request->get('status') == 'on' ? 1 : 0,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id()
        ])->save();
        session()->flash('update_success_info', '" WorkActivity updated successfully."');
        return redirect()->route('work-activity.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkActivity $work_activity)
    {
        if(Input::get('hardDelete')){
            $work_activity->forceDelete();
        }else{
            $work_activity->delete();
        }
        session()->flash('delete_success_info','" material deleted "');
        return redirect()->route('month.index');
    }
}
