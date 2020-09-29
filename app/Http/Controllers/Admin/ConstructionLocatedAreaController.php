<?php

namespace PMIS\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use PMIS\ConstructionLocatedArea;
use PMIS\Division;
use PMIS\Http\Controllers\AdminBaseController;

class ConstructionLocatedAreaController extends AdminBaseController
{
    protected $pro_data;
    protected $construction_located_areas;
    protected $route = "construction-located-area";


    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            restrictToImplementingOffice('abort');
            $this->pro_data['model'] = "Construction Located Area";
            $this->pro_data['route'] = $this->route;
            $this->construction_located_areas = $this->user_info->ConstructionLocatedArea();
            return $next($request);
        });

    }

    public function index()
    {

        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        if (isset($_GET['search'])) {
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['construction_located_areas'] = $this->construction_located_areas->search($_GET['search']);
            $this->pro_data['other_data'] = '&search=' . $_GET['search'];
            if ($this->pro_data['construction_located_areas']->get()->isEmpty()) {
                $this->pro_data['not_found'] = 'Sorry! could not find your content. Please try with another keywords.';
            }
        } else {
            $this->pro_data['construction_located_areas'] = $this->construction_located_areas;
        }
        if (isset($_GET['orderBy']) && isset($_GET['order'])) {
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order']) == 'asc') ? 'desc' : 'asc';
        }

        $this->pro_data['construction_located_areas'] = $this->pro_data['construction_located_areas']->orderBy($this->pro_data['orderBy'], $this->pro_data['order'])->simplePaginate(10);
        return view('admin.construction_located_area.index', $this->pro_data);
    }

    public function create()
    {
        return view('admin.construction_located_area.create', $this->pro_data);
    }

    public function store(Request $request)
    {
        $status = $request->get('status') == 'on' ? 1 : 0;
        $cla = $request->all();
        $cla['status'] = $status;
        $cla['monitoring_office_id'] = $this->user_info->implementingOffice->id;
        ConstructionLocatedArea::create(
            $cla
        );
        session()->flash('store_success_info', '" Area name ' . $request->get('located_area') . '"');
        return redirect()->route("$this->route.index");
    }

    public function show()
    {

    }

    public function edit(ConstructionLocatedArea $area, $id)
    {
        try {
            $this->pro_data['construction_located_area'] = $area->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
        return view('admin.construction_located_area.edit', $this->pro_data);
    }

    public function update(Request $request, ConstructionLocatedArea $area, $id)
    {
        try {
            $area = $area->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
        $status = $request->get('status') == 'on' ? 1 : 0;
        $cla = $request->all();
        $cla['status'] = $status;

        $area->fill(
            $cla
        )->save();
        session()->flash('store_success_info', '" Area named ' . $request->get('located_area') . '"');
        return redirect()->route("$this->route.index");
    }

    public function destroy(Division $division)
    {

    }
}
