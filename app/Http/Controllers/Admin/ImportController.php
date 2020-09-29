<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PMIS\BudgetTopic;
use PMIS\ExpenditureTopic;
use PMIS\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use PMIS\Allocation;
use PMIS\Http\Requests\ImportProjectRequest;
use PMIS\LumpSumBudget;
use PMIS\Procurement;
use PMIS\Progress;
use PMIS\Project;
use PMIS\ProjectGroup;
use PMIS\ProjectSetting;
use PMIS\TimeExtension;
use PMIS\Variation;

class ImportController extends AdminBaseController {
    protected $procurementMap=array();
    protected $excelFormat = 'csv';



    public function index(){

        return view('admin.import.index',$this->pro_data);
    }


    public function ProjectImport(ImportProjectRequest $request){
        $procurementMap=array();
        Excel::load($request->file('projects'), function($reader)use ($procurementMap){
            $reader->each(function($sheet)use($procurementMap){
                if($sheet->project_code == ''){
                    $sheet['project_code'] = getProjectCode($sheet->implementing_office_id,$sheet->monitoring_office_id);
                }
                $sheet['name'] = trim(preg_replace('/\s\s+/', ' ', $sheet->name));
//                echo $sheet['project_code'];
                $sheet = $sheet->toArray();
                $sheet_without_contingency = $sheet;
                if(array_key_exists('contingency',$sheet)){
                    unset($sheet_without_contingency['contingency']);
                }
                if(array_key_exists(0,$sheet)){
                    unset($sheet_without_contingency[0]);
                }
                $project=Project::firstOrCreate($sheet_without_contingency);
                $project_settings = ProjectSetting::insert([
                    'code'=> $sheet['project_code'],
                    'project_id'=> $project->id,
                    'fy'=> $project->fy_id,
                    'budget_id'=> $project->budget_topic_id,
                    'expenditure_id'=> $project->expenditure_topic_id,
                    'implementing_id'=> $project->implementing_office_id,
                    'monitoring_id'=> $project->monitoring_office_id,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                ]);
                if($project->procurement){
                    $procurementId=$project->procurement->id;
                }else{
                    $procurementId=$project->procurement()->firstOrCreate([
                        'status'=>0,
                        'contingency'=>$sheet['contingency']??5
                    ])->id;
                }
                $this->procurementMap[$procurementId]=array(0=>$project->id,1=>$project->lmbis_code,2=>$project->project_code);
                $description=logDescriptionCreate($sheet);
                storeLog($project->id,$description,0 ,'Project File Import');
            });
        });
        $heading[0] = "Project Code";
        $heading[1] = "Lmbis Code";
        $heading[2] = "Project Code";
        $heading[3] = "procurement_id";
        $exportTable[]=$heading;

        foreach($this->procurementMap as $procurementId=>$map){
            $exportField[0]=$map[0];
            $exportField[1]=$map[1];
            $exportField[2]=$map[2];
            $exportField[3]=$procurementId;
            $exportTable[] = $exportField;
        }

        Excel::create('project_procurement'.date('Y-m-d'), function($excel) use( $exportTable){
            $excel->sheet('mapping', function($sheet) use( $exportTable ) {
                $sheet->fromArray( $exportTable,null,'A1',false,false);
            });
        })->download($this->excelFormat);
        session()->flash('store_success_info',' Excel File Imported successfully');
        return redirect()->back();
    }

    public function ProcurementImport(Request $request){
        Excel::load($request->file('procurements'), function($reader){
            $reader->each(function($sheet){
                $procurement=Procurement::find($sheet['procurement_id']);
                $excelProcurement=$sheet->toArray();
                unset($excelProcurement['procurement_id']);
                unset($excelProcurement['project_code']);
                $procurement->fill($sheet->toArray())->save();
                $description=logDescriptionCreate($sheet);
                storeLog($sheet['project_code'],$description,0 ,'Procurement File Import');
            });
        });
        session()->flash('store_success_info',' Excel File Imported successfully');
        return redirect()->back();
    }

    public function progressImport(Request $request){
        Excel::load($request->file('progress'), function($reader){
            $reader->each(function($sheet){
                Progress::firstOrCreate($sheet->toArray());
                $description=logDescriptionCreate($sheet);
                storeLog($sheet['project_code'],$description,0 ,'Progress File Import');
            });
        });
        session()->flash('store_success_info',' Excel File Imported successfully');
        return redirect()->back();
    }

    public function allocationImport(Request $request){
        Excel::load($request->file('allocation'), function($reader){
            $reader->each(function($sheet){
                $sheet = $sheet->toArray();
                if(array_key_exists(0,$sheet)){
                    unset($sheet[0]);
                }
                Allocation::firstOrCreate($sheet);
                $description=logDescriptionCreate($sheet);
                storeLog(null,$description,0 ,'Allocation File Import');
            });
        });
        session()->flash('store_success_info',' Excel File Imported successfully');
        return redirect()->back();
    }

    public function vopeImport(Request $request){
        Excel::load($request->file('vope'), function($reader){
            $reader->each(function($sheet){
                Variation::firstOrCreate($sheet->toArray());
                $description=logDescriptionCreate($sheet);
                storeLog($sheet['project_code'],$description,0 ,'Vope File Import');
            });
        });
        session()->flash('store_success_info',' Excel File Imported successfully');
        return redirect()->back();
    }

    public function time_extensionImport(Request $request){
        Excel::load($request->file('time_extension'), function($reader){
            $reader->each(function($sheet){
                TimeExtension::firstOrCreate($sheet->toArray());
                $description=logDescriptionCreate($sheet);
                storeLog($sheet['project_code'],$description,0 ,'Time Extension File Import');
            });
        });
        session()->flash('store_success_info',' Excel File Imported successfully');
        return redirect()->back();
    }

    public function groupImport(Request $request){
        Excel::load($request->file('group'), function($reader){
            $reader->each(function($sheet){
                ProjectGroup::firstOrCreate($sheet->toArray());
            });
        });
        session()->flash('store_success_info','Excel File Imported successfully ');
        return redirect()->back();
    }

    public function fileModules()
    {
        $file="public/Import Excels.zip";
        return response()->download($file);
    }

    public function __construct(){
        parent::__construct();
        $this->middleware(function ($request, $next) {
            restrictToImplementingOffice('abort');
            return $next($request);
        });
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');
    }

}
