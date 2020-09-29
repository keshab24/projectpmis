<?php

namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\ProcurementDate;

class ProcurementDateController extends AdminBaseController
{
    protected $pro_data;
    protected $procurement_date;

    public function __construct(ProcurementDate $procurement_date)
    {
        parent::__construct();
        $this->procurement_date = $procurement_date;
    }

    public function index()
    {
        abort(404);
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {

        abort(404);
        $status = $request->get('status') == 'on' ? 1 : 0;
        $this->zone->create([
            'name' => $request->get('name'),
            'name_eng' => $request->get('name_eng'),
            'description' => $request->get('description'),
            'description_eng' => $request->get('description_eng'),
            'coordinates' => $request->get('coordinates'),
            'amount' => $request->get('amount'),
            'region_id' => $request->get('region_id'),
            'status' => $status
        ]);
        $description = logDescriptionCreate($request->all());
        storeLog(null, $description, 0, 'Zone');

        session()->flash('store_success_info', '" zone name ' . $request->get('name') . '"');
        return redirect()->route('zone.create');
    }

    public function show()
    {
        abort(404);
    }

    public function edit(ProcurementDate $procurementDate)
    {
        if (!allowEdit($procurementDate)) {
            abort(403);
        }
        $this->pro_data['procurement_date'] = $procurementDate;
        return view('admin.procurement_date.edit', $this->pro_data);
    }

    public function update(Request $request, ProcurementDate $procurementDate)
    {
        if (!allowEdit($procurementDate)) {
            abort(403);
        }
        $oldProcurementDate = $procurementDate->toArray();
        $fileName = $procurementDate->file;

        if ($request->has('remove_current_file')) {
            if ($procurementDate->file) {
                if (file_exists('public/activityFiles/' . $procurementDate->file)) {
                    unlink('public/activityFiles/' . $procurementDate->file);
                }
            }
            $fileName = null;
        }
        if ($request->hasFile('file')) {
            if ($procurementDate->file) {
                if (file_exists('public/activityFiles/' . $procurementDate->file)) {
                    unlink('public/activityFiles/' . $procurementDate->file);
                }
            }
            $file = $request->file('file');
            $fileName = getFileName($file);
            $mime = $file->getMimeType();
            if ($mime == 'image/jpeg' || $mime == 'image/pjpeg' || $mime == 'image/gif' || $mime == 'image/gif' || $mime == 'image/png' || $mime == 'application/pdf') {
                $file->move('public/activityFiles', $fileName);
            }
        }

        $procurementDate->fill([
            'reference_number' => $request->get('reference_number'),
            'company_name' => $request->get('company_name'),
            'company_branch' => $request->get('company_branch'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'type' => $request->get('type'),
            'amount' => $request->get('amount'),
            'file' => $fileName,
        ])->save();
        $change = logDescriptionUpdate($procurementDate, $oldProcurementDate);
        if ($change != false) {
            storeLog(null, $change, 1, 'Procurement Date');
        }
        session()->flash('update_success_info', '" Procurement date ' . procurementDates()[$request->get('type')] . '"');
        return redirect()->back();
    }

    public function destroy()
    {
        abort(404);
    }
}
