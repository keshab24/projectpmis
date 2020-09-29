<?php

namespace PMIS\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\Project;
use PMIS\ProjectSetting;

class ProjectSettingController extends AdminBaseController
{
    protected $project_setting;

    public function __construct(ProjectSetting $project_setting)
    {
        parent::__construct();
        $this->middleware(function ($request, $next) use ($project_setting) {
            restrictEngineers($this->user_info->type_flag);
            $this->project_setting = $project_setting;
            return $next($request);
        });
    }

    public function index()
    {
        $projects = Project::get();
        foreach ($projects as $project) {
            $project->projectSettings()->create([
                'fy' => 16,
                'code' => $project->getOriginal('project_code'),
                'budget_id' => $project->budget_topic_id,
                'expenditure_id' => $project->expenditure_topic_id,
                'implementing_id' => $project->implementing_office_id,
                'monitoring_id' => $project->monitoring_office_id,

                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
        }
        foreach ($projects as $project) {
            if($project->show_on_running == '1'){
                $project->projectSettings()->create([
                    'fy' => 17,
                    'code' => $project->getOriginal('project_code'),
                    'budget_id' => $project->budget_topic_id == 34 ? 35 : ($project->budget_topic_id ==9  ? 36 : $project->budget_topic_id),
                    'expenditure_id' => $project->expenditure_topic_id == 19 ? 58 :  $project->expenditure_topic_id,
                    'implementing_id' => $project->implementing_office_id,
                    'monitoring_id' => $project->monitoring_office_id,

                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                ]);
            }/*else{
                $project->projectSettings()->create([
                    'fy' => 17,
                    'code' => $project->project_code,
                    'budget_id' => $project->budget_topic_id,
                    'expenditure_id' => $project->expenditure_topic_id,
                    'implementing_id' => $project->implementing_office_id,
                    'monitoring_id' => $project->monitoring_office_id,

                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                ]);
            }*/
        }
        dd(1);
    }

    public function design()
    {
        dd('design');
        $projects = Project::where('monitoring_office_id', 397)->get();
        foreach ($projects as $project) {
            $project->projectSettings()->create([
                'fy' => 17,
                'code' => $project->getOriginal('project_code'),
                'budget_id' => $project->budget_topic_id,
                'expenditure_id' => $project->expenditure_topic_id,
                'implementing_id' => $project->implementing_office_id,
                'monitoring_id' => $project->monitoring_office_id,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
        }
    }
}
