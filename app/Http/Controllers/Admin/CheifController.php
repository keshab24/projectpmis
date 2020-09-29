<?php

namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use PMIS\Cheif;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\Http\Requests\CreateCheifRequest;

class CheifController extends AdminBaseController
{
    protected $pro_data;
    protected $cheif;

    public function __construct(Cheif $cheif)
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            restrictToImplementingOffice('abort');
            return $next($request);
        });
        $this->pro_data['trashes_no'] = $cheif->onlyTrashed()->count();
        $this->cheif = $cheif->whereStatus(1);
    }

    public function index()
    {
        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        if (isset($_GET['search'])) {
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['cheifs'] = $this->cheif->search($_GET['search']);
            $this->pro_data['other_data'] = '&search=' . $_GET['search'];
            if ($this->pro_data['cheifs']->get()->isEmpty()) {
                $this->pro_data['not_found'] = 'Sorry! could not find your content. Please try with another keywords.';
            }
        } else {
            $this->pro_data['cheifs'] = $this->cheif;
        }

        if (isset($_GET['orderBy']) && isset($_GET['order'])) {
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order']) == 'asc') ? 'desc' : 'asc';
        }
        if (isset($_GET['trashes'])) {
            $this->pro_data['cheifs'] = $this->pro_data['cheifs']->onlyTrashed();
        }
        $this->pro_data['cheifs'] = $this->pro_data['cheifs']->orderBy($this->pro_data['orderBy'], $this->pro_data['order'])->paginate(10);

        return view('admin.cheif.index', $this->pro_data);
    }

    public function create()
    {
        return view('admin.cheif.create', $this->pro_data);
    }

    public function store(CreateCheifRequest $request)
    {
        $status = $request->get('status') == 'on' ? 1 : 0;
        $image = $request->file('image');

        $fileName = NULL;
        if ($image != null && $image != '' && !empty($image) && isset($image)) {
            $fileName = makeImageName($image->getClientOriginalName());
            $this->uploadImage('office_cheif', $fileName, $image);
        }

        Cheif::create([
            'name' => $request->get('name'),
            'nep_name' => $request->get('nep_name'),
            'address' => $request->get('address'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'mobile' => $request->get('mobile'),
            'image' => $fileName,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'status' => $status
        ]);

        $description = logDescriptionCreate($request->all());
        storeLog(null, $description, 0, 'Office Chief');

        session()->flash('store_success_info', '" cheif name ' . $request->get('name') . '"');
        return redirect()->route('cheif.create');
    }

    public function show()
    {

    }

    public function edit(Cheif $cheif)
    {
        $this->pro_data['cheif'] = $cheif;
        return view('admin.cheif.edit', $this->pro_data);
    }

    public function update(CreateCheifRequest $request, Cheif $cheif)
    {
        $oldChief = $cheif->toArray();
        $status = $request->get('status') == 'on' ? 1 : 0;
        $image = $request->file('image');
        $fileName = $oldFileName = $cheif->image;
        if ($image != null && $image != '' && !empty($image) && isset($image)) {
            $fileName = makeImageName($image->getClientOriginalName());
            $this->uploadImage('members', $fileName, $image, $oldFileName);
        }
        $cheif->fill([
            'name' => $request->get('name'),
            'nep_name' => $request->get('nep_name'),
            'address' => $request->get('address'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'mobile' => $request->get('mobile'),
            'image' => $fileName,
            'updated_by' => Auth::user()->id,
            'status' => $status
        ])->save();
        $change = logDescriptionUpdate($cheif, $oldChief);
        if ($change != false) {
            storeLog(null, $change, 1, 'Office Chief');
        }

        session()->flash('update_success_info', '" cheif named ' . $request->get('name') . '"');
        return redirect()->route('cheif.index');
    }

    public function destroy(Cheif $cheif)
    {
        $name = $cheif->name;
        if (Input::get('hardDelete')) {
            $cheif->forceDelete();
        } else {
            $cheif->delete();
        }
        storeLog(null, $name, 2, 'Office Chief');
        session()->flash('delete_success_info', '" cheif named ' . $name . '"');
        return redirect()->route('cheif.index');
    }
}
