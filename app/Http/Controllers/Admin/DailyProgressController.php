<?php

namespace PMIS\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use PMIS\ActivityLog;
use PMIS\ActivityLogFiles;
use PMIS\DailyProgress;
use PMIS\DateCon;
use PMIS\Equipment;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\Http\Controllers\Controller;
use PMIS\Manpower;
use PMIS\Material;
use PMIS\Project;
use PMIS\ProjectBlocks;
use PMIS\WorkActivity;

class DailyProgressController extends AdminBaseController
{
    public function __construct(){
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if($this->user_info->implementing_office_id !== 410){
                return abort(404);
            }
            return $next($request);
        });
    }

    public function create(Project $project, Manpower $manpower, Equipment $equipment, Material $material, WorkActivity $work_activity){
        $this->pro_data['project'] = $project;
        $this->pro_data['manpowers'] = $manpower->whereStatus(1)->orderBy('order')->get();
        $this->pro_data['equipments'] = $equipment::active()->select('title','unit','status','id','type')->get();
        $this->pro_data['materials'] = $material::active()->select('title','unit','status','id','type')->get();
        $this->pro_data['activities'] = $work_activity::active()->select('title','status','id','type','code','unit')->get();
        $this->pro_data['weathers'] = ['Sunny' => 'Sunny', 'Cloudy' => 'Cloudy', 'Rainning' => 'Rainning'];
        $this->pro_data['activity_statuses'] = ['No Act' => 'No Act', 'On progress' => 'On progress', 'Done' => 'Done', 'Completed' => 'Completed'];

        if (isset($_GET['today'])){
            $daily_progress = $project->dailyProgress()->where('date', date('Y-m-d'))->latest()->first();
            $this->pro_data['daily_progress'] = $daily_progress;
        }else{
            $daily_progress = null;
        }

        $this->pro_data['existing_logs'] = ActivityLog::where('submitted_date', dateBS(date('Y-m-d')))->where('project_id', $project->id)->get();
        $this->pro_data['existing_files'] = ActivityLogFiles::get();

        $this->pro_data['blocks'] = add_my_array(ProjectBlocks::whereProjectId($project->id)->pluck('block_name', 'id'), "Select Block");
        $this->pro_data['editable_daily_progress'] = $project->dailyProgress()->where('date', '>=', dateBS(Carbon::now()->subDays(2)->format('Y-m-d')))->orderBy('date', 'desc')->get();

        return view('admin.project.daily-progress', $this->pro_data);
    }

    public function store(Project $project, Request $request)
    {
        $request->validate([
            'date' => 'required | unique:daily_progresses'
        ]);
        $daily_progress = $project->dailyProgress()->where('date', date('Y-m-d'))->latest()->first();
        if (!$daily_progress)
            $daily_progress = new DailyProgress();
        $daily_progress->fill($request->all());
        $activities = $request->get('activities');
        $activity_array = [];
        if(is_array($activities)){
            foreach($activities as $field => $activity){
                $i = 0;
                foreach($activity as $index => $fields){
                    $activity_array[$i][$field] = $fields;
                    $i++;
                }
            }
        }

        $samples = $request->get('samples');
        $samples_array = [];
        if (is_array($samples)) {
            foreach ($samples as $sample_field => $sample_values) {
                $i = 0;
                foreach ($sample_values as $sample_value) {
                    $samples_array[$i][$sample_field] = $sample_value;
                    $i++;
                }
            }
        }

        $daily_progress->fill([
//            'date' => date('Y-m-d'),
            'project_id' => $project->id,
            'created_by' => Auth()->user()->id,
            'updated_by' => Auth()->user()->id,
            'activities' => $activity_array,
            'samples' => $samples_array,
        ])->save();

        $this->storeActivityPhoto($project, $daily_progress);

        return redirect()->route('daily.progress.edit', $daily_progress->id);
    }

    public function edit(DailyProgress $dailyProgress, Project $project, Manpower $manpower, Equipment $equipment, Material $material, WorkActivity $work_activity){
        $this->pro_data['daily_progress'] = $dailyProgress;
        $project = $dailyProgress->project;
        $this->pro_data['manpowers'] = $manpower->whereStatus(1)->orderBy('order')->get();
        $this->pro_data['equipments'] = $equipment::active()->select('title','unit','status','id','type')->get();
        $this->pro_data['materials'] = $material::active()->select('title','unit','status','id','type')->get();
        $this->pro_data['activities'] = $work_activity::active()->select('title','status','id','type','code','unit')->get();
        $this->pro_data['weathers'] = ['Sunny' => 'Sunny', 'Cloudy' => 'Cloudy', 'Rainning' => 'Rainning'];
        $this->pro_data['activity_statuses'] = ['No Act' => 'No Act', 'On progress' => 'On progress', 'Done' => 'Done', 'Completed' => 'Completed'];
        $this->pro_data['existing_files'] = ActivityLogFiles::get();
        $this->pro_data['existing_logs'] = ActivityLog::where('submitted_date', dateBS(date('Y-m-d')))->where('project_id', $project->id)->get();

        $this->pro_data['blocks'] = add_my_array(ProjectBlocks::whereProjectId($project->id)->pluck('block_name', 'id'), "Select Block");
        $this->pro_data['editable_daily_progress'] = $project->dailyProgress()->where('date', '>=', dateBS(Carbon::now()->subDays(2)->format('Y-m-d')))->orderBy('date', 'desc')->get();
        $this->pro_data['project'] = $project;
        return view('admin.project.daily-progress-edit', $this->pro_data);
    }

    public function update(DailyProgress $dailyProgress, Request $request){
        $project = $dailyProgress->project;
        $dailyProgress->fill($request->all());
        $activities = $request->get('activities');
        $activity_array = [];
        if(is_array($activities)){
            foreach($activities as $field => $activity){
                $i = 0;
                foreach($activity as $index => $fields){
                    $activity_array[$i][$field] = $fields;
                    $i++;
                }
            }
        }

        $samples = $request->get('samples');
        $samples_array = [];
        if (is_array($samples)) {
            foreach ($samples as $sample_field => $sample_values) {
                $i = 0;
                $manpower_types = ["for_client", "for_consultant", "for_contractor"];
                foreach ($sample_values as $sample_value) {
                        $samples_array[$i][$sample_field] = $sample_value;
                        $i++;
                }
            }
        }

        $dailyProgress->fill([
            'project_id' => $project->id,
            'created_by' => Auth()->user()->id,
            'updated_by' => Auth()->user()->id,
            'activities' => $activity_array,
            'samples' => $samples_array,
        ])->save();

        $this->storeActivityPhoto($project);

        return redirect()->back();
    }

    public function storeActivityPhoto($project)
    {
        $activitylog = ActivityLog::create([
            'title' => 'Progress Photo',
            'description' => 'Progress Photo',
//            'submitted_date' => date('Y-m-d'),
            'submitted_date' => request()->date,
            'implementing_office_id' => $project->implementing_office_id,
            'type' => 2,//progress photo
            'project_id' => $project->id,
            'created_by' => $this->user_info->id,
            'updated_by' => $this->user_info->id,
            'status' => 1
        ]);

        if (request()->hasFile('file')) {
            foreach (request()->file('file') as $index => $file) {
                $fileName = getFileName($file);
                $mime = $file->getMimeType();
                if ($mime == 'image/jpeg' || $mime == 'image/pjpeg' || $mime == 'image/gif' || $mime == 'image/gif' || $mime == 'image/png' || $mime == 'application/pdf' || $mime == 'video/mp4' || $mime == 'video/ogg' || $mime == "video/webm") {
                    $file->move('public/activityFiles', $fileName);
                }
                ActivityLogFiles::create([
                    'activity_log' => $activitylog->id,
                    'file_path' => $fileName,
                    'type' => request()->type[$index],
                    'description' => request()->description[$index],
                ]);
            }

        }
    }

    public function report(Project $project, Request $request){
        $this->pro_data['project'] = $project;

        if(isset($_GET['today'])){
            $progress_reports = $project->dailyProgress()->where('date',dateBS(date('Y-m-d')))->latest()->groupBy('date')->orderBy('date')->get();
        }else{
            $progress_reports = $project->dailyProgress()->whereBetween('date', [$request->from, $request->to])->latest()->groupBy('date')->orderBy('date')->get();
        }

        $this->pro_data['progresses'] = $progress_reports;
//        dd($this->pro_data['progresses']->first()->getOriginal());
        return view('admin.report.daily-progress-report', $this->pro_data);
    }
}
