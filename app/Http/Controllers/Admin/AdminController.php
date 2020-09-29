<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PMIS\Address;
use PMIS\Allocation;
use PMIS\AuthorizedPerson;
use PMIS\BudgetTopic;
use PMIS\ConstructionType;
use PMIS\Contractor;
use PMIS\CurrentProgress;
use PMIS\District;
use PMIS\Division;
use PMIS\Divisionchief;
use PMIS\Document;
use PMIS\Employee;
use PMIS\Engineer;
use PMIS\ExpenditureTopic;
use PMIS\Fiscalyear;
use PMIS\FundStore;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\ImplementingMode;
use PMIS\ImplementingOffice;
use PMIS\IncomeTopic;
use PMIS\JointVenture;
use PMIS\LumpSumBudget;
use PMIS\Month;
use PMIS\Notice;
use PMIS\NotificationType;
use PMIS\Procurement;
use PMIS\Progress;
use PMIS\ProgressTrack;
use PMIS\Project;
use PMIS\ProjectGroup;
use PMIS\ProLog;
use PMIS\Region;
use PMIS\SalaryHead;
use PMIS\Sector;
use PMIS\State;
use PMIS\UserTypeFlag;
use PMIS\Vendor;
use PMIS\Zone;

class AdminController extends AdminBaseController {
    protected $pro_data;
    public function index(){
        return view('admin.program', $this->pro_data);
    }
    public function manage(){
        return view('admin.manage', $this->pro_data);
    }
    public function program(){
        return view('admin.program', $this->pro_data);
    }
    public function administration(){
        return view('admin.administration', $this->pro_data);
    }
    public function capital(){
        return view('admin.capital', $this->pro_data);
    }
    public function misc(){
        return view('admin.misc', $this->pro_data);
    }
    public function allowance(){
        return view('admin.allowance', $this->pro_data);
    }
    public function reports_print(){
        return view('admin.reports_print', $this->pro_data);
    }

    public function logout(){
        session()->forget('pro_fiscal_year');
        session()->forget('month_id_session');
        session()->forget('pro_budget_topic');
        session()->forget('pro_expenditure_topic');
        session()->forget('pro_implementing_office');
        session()->forget('pro_division_office');
        Auth::logout();
        return redirect()->route('home');
    }

    public function set_fiscal_year(Request $request){
        session()->put('pro_fiscal_year', $request->get('fiscal_year'));
        return redirect()->back();
    }

    public function set_month_id(Request $request){
        session()->put('month_id_session', $request->get('month_id'));
        return redirect()->back();
    }

    public function set_month_id_summary(Request $request){
        session()->put('month_id_session_summary', $request->get('month_id'));
        return redirect()->back();
    }

    public function set_budget_topic(Request $request){
        session()->put('pro_expenditure_topic', $request->get('expenditure_topic'));
        session()->put('pro_budget_topic', $request->get('budget_topic'));
        session()->put('pro_implementing_office', $request->get('implementing_office'));
        session()->put('month_id_session', $request->get('month_id'));
        return redirect()->back();
    }
    public function set_expenditure_topic(Request $request){
        session()->put('pro_expenditure_topic', $request->get('expenditure_topic'));
        return redirect()->back();
    }

    public function set_implementing_office(Request $request){
        session()->put('pro_implementing_office', $request->get('implementing_office'));
        return redirect()->back();
    }
    public function set_division_office(Request $request){
        session()->put('pro_division_office', $request->get('division_code'));
        return redirect()->back();
    }
    public function set_destroy_session(){
        session()->forget('pro_budget_topic');
        session()->forget('pro_expenditure_topic');
        session()->forget('pro_implementing_office');
        session()->forget('pro_division_office');
        session()->forget('month_id_session');
        return redirect()->back();
    }
    public function set_destroy_session_summary(){
        session()->forget('month_id_session_summary');
        return redirect()->back();
    }
    public function downloadFile($filePath, $fileName){
        $pathToFile = 'public/images/'.$filePath.'/original/orivtx'.$fileName;
        return response()->download($pathToFile);
    }

    public function deleteFile($filePath, $slug, $fileName){
        $this->removeFiles($filePath,$fileName);
        switch($filePath){
            case 'DivisionChief':
                $data = DivisionChief::withTrashed()->whereSlug($slug)->first();
                $data->image = '';
                $data->save();
                break;

        }
        session()->flash('delete_success_info','" file name named '.$fileName.'"');
        return redirect()->back();
    }

    public function deleteMass(Request $request){
        if($this->user_info->access >= 2){abort(403);}
        $modelName = $request->get('model');
        $hardDelete = $request->get('hard_delete');
        switch($modelName){
            case 'Zone':
                foreach($request->get('mass_name') as $nameId){
                    $zone = Zone::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('zone',$zone->image);
                        $zone->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $zone->delete();
                    }
                }
                break;
            case 'Region':
                foreach($request->get('mass_name') as $nameId){
                    $region = Region::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('region',$region->image);
                        $region->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $region->delete();
                    }
                }
                break;
            case 'District':
                foreach($request->get('mass_name') as $nameId){
                    $district = District::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('district',$district->image);
                        $district->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $district->delete();
                    }
                }
                break;
            case 'Document':
                foreach($request->get('mass_name') as $nameId){
                    $document = Document::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('document',$document->attachment);
                        $document->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $document->delete();
                    }
                }
                break;
            case 'Division':
                foreach($request->get('mass_name') as $nameId){
                    $division = Division::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('district',$division->image);
                        $division->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $division->delete();
                    }
                }
                break;
            case 'Address':
                foreach($request->get('mass_name') as $nameId){
                    $address = Address::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('address',$address->image);
                        $address->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $address->delete();
                    }
                }
                break;
            case 'Engineer':
                foreach($request->get('mass_name') as $nameId){
                    $engineer = Engineer::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('divisionchief',$engineer->image);
                        $engineer->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $engineer->delete();
                    }
                }
                break;

            case 'Month':
                foreach($request->get('mass_name') as $nameId){
                    $month = Month::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('month',$month->image);
                        $month->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $month->delete();
                    }
                }
                break;

            case 'Fiscalyear':
                foreach($request->get('mass_name') as $nameId){
                    $fiscalyear = Fiscalyear::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('fiscalyear',$fiscalyear->image);
                        $fiscalyear->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $fiscalyear->delete();
                    }
                }
                break;

            case 'Progresstrack':
                foreach($request->get('mass_name') as $nameId){
                    $progresstrack = ProgressTrack::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('progresstrack',$progresstrack->image);
                        $progresstrack->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $progresstrack->delete();
                    }
                }
                break;

            case 'Budgettopic':
                foreach($request->get('mass_name') as $nameId){
                    $budgettopic = BudgetTopic::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('budgettopic',$budgettopic->image);
                        $budgettopic->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $budgettopic->delete();
                    }
                }
                break;

            case 'Expendituretopic':
                foreach($request->get('mass_name') as $nameId){
                    $expendituretopic = ExpenditureTopic::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('expendituretopic',$expendituretopic->image);
                        $expendituretopic->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $expendituretopic->delete();
                    }
                }
                break;

            case 'IncomeTopic':
                foreach($request->get('mass_name') as $nameId){
                    $incomeTopic = IncomeTopic::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $incomeTopic->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $incomeTopic->delete();
                    }
                }
                break;

            case 'Lumpsumbudget':
                foreach($request->get('mass_name') as $nameId){
                    $lumpsumbudget = LumpSumBudget::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('lumpsumbudget',$lumpsumbudget->image);
                        $lumpsumbudget->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $lumpsumbudget->delete();
                    }
                }
                break;

            case 'Constructiontype':
                foreach($request->get('mass_name') as $nameId){
                    $constructiontype = ConstructionType::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('constructiontype',$constructiontype->image);
                        $constructiontype->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $constructiontype->delete();
                    }
                }
                break;

            case 'Sector':
                foreach($request->get('mass_name') as $nameId){
                    $sector = Sector::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('sector',$sector->image);
                        $sector->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $sector->delete();
                    }
                }
                break;

            case 'Implementingoffice':
                foreach($request->get('mass_name') as $nameId){
                    $implementingoffice = ImplementingOffice::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('implementingoffice',$implementingoffice->image);
                        $implementingoffice->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $implementingoffice->delete();
                    }
                }
                break;

            case 'ProjectGroup':
                foreach($request->get('mass_name') as $nameId){
                    $projectGroup = ProjectGroup::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('projectGroup',$projectGroup->image);
                        $projectGroup->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $projectGroup->delete();
                    }
                }
                break;

            case 'Project':
                foreach($request->get('mass_name') as $nameId){
                    $project = Project::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('project',$project->image);
                        $project->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $project->delete();
                    }
                }
                break;

            case 'Implementinmode':
                foreach($request->get('mass_name') as $nameId){
                    $implementingmode = ImplementingMode::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('implementingmode',$implementingmode->image);
                        $implementingmode->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $implementingmode->delete();
                    }
                }
                break;

            case 'Procurement':
                foreach($request->get('mass_name') as $nameId){
                    $prcurement = Procurement::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('prcurement',$prcurement->image);
                        $prcurement->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $prcurement->delete();
                    }
                }
                break;

            case 'Allocation':
                foreach($request->get('mass_name') as $nameId){
                    $allocation = Allocation::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('allocation',$allocation->image);
                        $allocation->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $allocation->delete();
                    }
                }
                break;

            case 'Progress':
                foreach($request->get('mass_name') as $nameId){
                    $progress = Progress::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('progress',$progress->image);
                        $progress->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $progress->delete();
                    }
                }
                break;
            case 'SalaryHead':
                foreach($request->get('mass_name') as $nameId){
                    $progress = SalaryHead::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('salaryhead',$progress->image);
                        $progress->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $progress->delete();
                    }
                }
                break;
            case 'Vendor':
                foreach($request->get('mass_name') as $nameId){
                    $progress = Vendor::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('vendor',$progress->image);
                        $progress->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $progress->delete();
                    }
                }
                break;
            case 'Employee':
                foreach($request->get('mass_name') as $nameId){
                    $progress = Employee::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('employee',$progress->image);
                        $progress->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $progress->delete();
                    }
                }
                break;
            case 'FundStore':
                foreach($request->get('mass_name') as $nameId){
                    $progress = FundStore::withTrashed()->find($nameId);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('fundstore',$progress->image);
                        $progress->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $progress->delete();
                    }
                }
                break;
            case 'AuthorizedPerson':
                foreach($request->get('mass_name') as $id){
                    $progress = AuthorizedPerson::withTrashed()->find($id);
                    if($hardDelete == 'yes'){
                        $this->removeFiles('fundstore',$progress->image);
                        $progress->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $progress->delete();
                    }
                }
                break;
            case 'State':
                foreach($request->get('mass_name') as $id){
                    $progress = State::withTrashed()->find($id);
                    if($hardDelete == 'yes'){
                        $progress->forceDelete();
                    }elseif($hardDelete == 'no'){
                        $progress->delete();
                    }
                }
                break;
        }
        session()->flash('delete_success_info','" selected items."');
        return redirect()->back();
    }

    public function restore($modelName, $value, $field='slug'){
        $item_name = ' selected item.';
        switch($modelName) {
            case 'Zone':
                Zone::withTrashed()->where($field,$value)->restore();
                $item_name = Zone::where($field,$value)->first()->name;
                break;
            case 'Document':
                $field = 'id';
                Document::withTrashed()->where($field,$value)->restore();
                $item_name = Document::where($field,$value)->first()->title;
                break;
            case 'State':
                State::withTrashed()->where($field,$value)->restore();
                $item_name = State::where($field,$value)->first()->name;
                break;
            case 'Region':
                Region::withTrashed()->where($field,$value)->restore();
                $item_name = Region::where($field,$value)->first()->name;
                break;
            case 'District':
                District::withTrashed()->where($field,$value)->restore();
                $item_name = District::where($field,$value)->first()->name;
                break;
            case 'Division':
                Division::withTrashed()->where($field,$value)->restore();
                $item_name = Division::where($field,$value)->first()->name;
                break;
            case 'Address':
                Address::withTrashed()->where($field,$value)->restore();
                $item_name = Address::where($field,$value)->first()->name;
                break;
            case 'Divisionchief':
                Divisionchief::withTrashed()->where($field,$value)->restore();
                $item_name = Divisionchief::where($field,$value)->first()->name;
                break;
            case 'Month':
                Month::withTrashed()->where($field,$value)->restore();
                $item_name = Month::where($field,$value)->first()->name;
                break;
            case 'Fiscalyear':
                Fiscalyear::withTrashed()->where($field,$value)->restore();
                $item_name = Fiscalyear::where($field,$value)->first()->fy;
                break;
            case 'Progresstrack':
                ProgressTrack::withTrashed()->where($field,$value)->restore();
                $item_name = ProgressTrack::where($field,$value)->first()->name;
                break;
            case 'Budgettopic':
                BudgetTopic::withTrashed()->where($field,$value)->restore();
                $item_name = BudgetTopic::where($field,$value)->first()->name;
                break;
            case 'Expendituretopic':
                ExpenditureTopic::withTrashed()->where($field,$value)->restore();
                $item_name = ExpenditureTopic::where($field,$value)->first()->name;
                break;
            case 'Lumpsumbudget':
                $field='id';
                LumpSumBudget::withTrashed()->where($field,$value)->restore();
                $item_name = LumpSumBudget::where($field,$value)->first();
                break;
            case 'Constructiontype':
                ConstructionType::withTrashed()->where($field,$value)->restore();
                $item_name = ConstructionType::where($field,$value)->first()->name;
                break;
            case 'Sector':
                Sector::withTrashed()->where($field,$value)->restore();
                $item_name = Sector::where($field,$value)->first()->name;
                break;
            case 'Implementingoffice':
                ImplementingOffice::withTrashed()->where($field,$value)->restore();
                $item_name = ImplementingOffice::where($field,$value)->first()->name;
                break;
            case 'ProjectGroup':
                ProjectGroup::withTrashed()->where($field,$value)->restore();
                $item_name = ProjectGroup::where($field,$value)->first()->name;
                break;
            case 'Project':
                $field='id';
                Project::withTrashed()->where($field,$value)->restore();
                $item_name = Project::where($field,$value)->first()->name;
                break;
            case 'Implementingmode':
                ImplementingMode::withTrashed()->where($field,$value)->restore();
                $item_name = ImplementingMode::where($field,$value)->first()->name;
                break;
            case 'Procurement':
                Procurement::withTrashed()->where($field,$value)->restore();
                $item_name = Procurement::where($field,$value)->first()->name;
                break;
            case 'Allocation':
                Allocation::withTrashed()->where($field,$value)->restore();
                $item_name = Allocation::where($field,$value)->first()->name;
                break;
            case 'Progress':
                Progress::withTrashed()->where($field,$value)->restore();
                $item_name = Progress::where($field,$value)->first()->name;
                break;
            //
            case 'Vendor':
                Vendor::withTrashed()->where($field,$value)->restore();
                $item_name = Vendor::where($field,$value)->first()->name;
                break;
            case 'Employee':
                Employee::withTrashed()->where($field,$value)->restore();
                $item_name = Employee::where($field,$value)->first()->name;
                break;
            case 'Salaryhead':
                SalaryHead::withTrashed()->where($field,$value)->restore();
                $item_name = SalaryHead::where($field,$value)->first()->name;
                break;
            case 'FundStore':
                FundStore::withTrashed()->where($field,$value)->restore();
                $item_name = FundStore::where($field,$value)->first()->name;
                break;
            case 'JointVenture':
                FundStore::withTrashed()->where($field,$value)->restore();
                $item_name = JointVenture::where($field,$value)->first()->name;
                break;
            case 'Notice':
                Notice::withTrashed()->where($field,$value)->restore();
                $item_name = Notice::where($field,$value)->first()->name;
                break;
        }
        storeLog(null,$item_name,3 ,$modelName);

        session()->flash('restore_info','" '.$item_name.'."');
        return redirect()->back();
    }

    public function changeBudgetGuide(Request $request)
    {
        session()->put('first_trimester_percentage_guide', trim($request->get('first_trimester_percentage_guide')));
        session()->put('second_trimester_percentage_guide', trim($request->get('second_trimester_percentage_guide')));
        session()->put('third_trimester_percentage_guide', trim($request->get('third_trimester_percentage_guide')));
        session()->flash('update_success_info','" session guide for all trimesters."');
        return redirect()->to($_SERVER['HTTP_REFERER']);
    }

    /**
     * @param BudgetTopic $budgetTopic
     * @param ExpenditureTopic $expenditureTopic
     * @param ImplementingOffice $implementingOffice
     * @param Division $division
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function summaryReport(BudgetTopic $budgetTopic, ExpenditureTopic $expenditureTopic, ImplementingOffice $implementingOffice, Division $division, Month $month){
        $month_id = [];
        if(session()->has('month_id_session_summary')){
            $this->pro_data['month'] = Month::whereId(session()->get('month_id_session_summary'))->first();
            $this->pro_data['trimester'] = $this->pro_data['month']->trimester;
        }else{
            $this->pro_data['month'] = Month::whereId(4)->first();;
            $this->pro_data['trimester'] = $this->pro_data['month']->trimester;
        }
        $this->pro_data['budgetTopics'] = $budgetTopic->has('projects')->get();
        $this->pro_data['budgetTopics'] = $budgetTopic->whereHas('projects',function($projects){
            $projects->whereHas('allocation',function($allocation){
                $allocation->whereFy_id(session()->get('pro_fiscal_year'));
            });
        })->get();
        $this->pro_data['months'] = $month->whereStatus(1)->pluck('name','id');
        return view('summaryReport',$this->pro_data);
    }

    /**
     * @param BudgetTopic $budgetTopic
     * @param ExpenditureTopic $expenditureTopic
     * @param ImplementingOffice $implementingOffice
     * @param Division $division
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function moreThanFifteenCorerSummary(BudgetTopic $budgetTopic, ExpenditureTopic $expenditureTopic, ImplementingOffice $implementingOffice, Division $division, Month $month){
        $month_id = [];

        if(session()->has('month_id_session_summary')){
            $this->pro_data['month'] = Month::whereId(session()->get('month_id_session_summary'))->first();
            $this->pro_data['trimester'] = $this->pro_data['month']->trimester;
        }else{
            $this->pro_data['month'] = Month::whereId(4)->first();;
            $this->pro_data['trimester'] = $this->pro_data['month']->trimester;
        }
        $this->pro_data['budgetTopics'] = $budgetTopic->has('projects')->get();
        $this->pro_data['budgetTopics'] = $budgetTopic->whereHas('projects',function($projects){
            $projects->whereHas('allocation',function($allocation){
                $allocation->whereFy_id(session()->get('pro_fiscal_year'));
            });
        })->get();
        $this->pro_data['months'] = $month->whereStatus(1)->pluck('name','id');
        return view('moreThanFifteenCorerSummary',$this->pro_data);
    }

    /**
     * @param BudgetTopic $budgetTopic
     * @param ExpenditureTopic $expenditureTopic
     * @param ImplementingOffice $implementingOffice
     * @param Division $division
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function divisionSummary(BudgetTopic $budgetTopic, ExpenditureTopic $expenditureTopic, ImplementingOffice $implementingOffice, Division $division){
        $this->pro_data['budgetTopics'] = $budgetTopic->has('projects')->pluck('budget_topic_num','id');
        $this->pro_data['expenditureTopics'] = $expenditureTopic->get();
        $this->pro_data['implementingOffice'] = add_my_array($implementingOffice->whereStatus(1)->where('level','>',0)->where('IsMonitoring',0)->get());
        $this->pro_data['monitoring_office']=array();
        if(Auth::user()->implementing_office_id!=1){
            if(Auth::user()->implementingOffice->isMonitoring==1){
                $this->pro_data['monitoring_office']=Auth::user()->implementingOffice()->pluck('id')->toArray();
                $this->pro_data['implementingOffice']=add_my_array(Auth::user()->implementingOffice->MonitorSeesImplementing);
            }else{
                $this->pro_data['monitoring_office']=Auth::user()->implementingOffice->implementingSeesMonitor->pluck('id')->toArray();
                $this->pro_data['implementingOffice']=Auth::user()->implementingOffice()->get();
            }
        }
        $this->pro_data['implementingOffices'] = $implementingOffice->get();


        return view('divisionReport',$this->pro_data);
    }


    public function mergeContractor(Contractor $contractor){
        restrictToImplementingOffice('abort');
        $contractors=$contractor->select('name','address','id')->whereStatus(1)->get();


        $contractorLists=array();
        foreach ($contractors as $contractor){
            $contractorLists[$contractor->id]=$contractor->name.', '.$contractor->address;
        }
        $this->pro_data['contractors']=$contractorLists;
        return view('admin.contractor.merge',$this->pro_data);
    }

    public function postMergeContractor(Request $request){

        if($request->get('replace_with')==0 || $request->get('old_contractor')==0){
            session()->flash('fail_info','"Please Choose a valid contractor."');
            return redirect()->back();
        }

        if($request->get('replace_with')==$request->get('old_contractor')){
            session()->flash('fail_info','"Same Contractor cannot be merged."');
            return redirect()->back();
        }


        $procurements= Procurement::whereContractor($request->get('old_contractor'))->get();
        if($procurements){
            foreach ($procurements as $procurement){
                $procurement->fill(['contractor'=>$request->get('replace_with')])->save();
            }
            $newContractor=Contractor::find($request->get('replace_with'))->name;
            $contractorDelete=Contractor::find($request->get('old_contractor'));
            $contractorDelete->forceDelete();
            $contractorDelete->myUser->forceDelete();
            $deleteContractorName=$contractorDelete->name;
        }
        session()->flash('update_success_info','" Contractor '.$deleteContractorName.' replaced with '.$newContractor.'"');
        return redirect()->back();
    }

    public function logAll(ProLog $proLog){
        restrictToImplementingOffice('abort');
        $this->pro_data['logs']=$proLog->orderBy('id', 'desc')->simplePaginate(50);
        return view('admin.log.all',$this->pro_data);
    }

    public function projectsLog(ProLog $proLog){
        restrictToImplementingOffice('abort');
        $this->pro_data['logs']=$proLog->has('project')->simplePaginate(50);
        $this->pro_data['onlyProjectsDetail']=true;
        return view('admin.log.all',$this->pro_data);
    }

    public function searchLog(ProLog $proLog)
    {
        restrictToImplementingOffice('abort');
        if(isset($_GET['search'])){
            $this->pro_data['logs']  = $proLog->orderBy('id', 'desc')->search($_GET['search'])->simplePaginate(50);
            return view('admin.log.all',$this->pro_data);
        }
        return redirect()->back();
    }

    public function ProjectLogDetail(ProLog $proLog,$id){
        restrictToImplementingOffice('abort');
        $this->pro_data['logs']=$proLog->orderBy('id', 'desc')->where('project_id',$id)->paginate(50);
        return view('admin.log.all',$this->pro_data);
    }

    public function logDetail(ProLog $log,$id)
    {
        restrictToImplementingOffice('abort');
        $logDetail=$log->whereId($id)->first();
        if(!$logDetail){
            abort(404);
        }
        $this->pro_data['log']=$logDetail;
        return view('admin.log.detail',$this->pro_data);
    }


    public function notificationManager(UserTypeFlag $userTypeFlag, NotificationType $notificationType){
        restrictToImplementingOffice('abort');
        $this->pro_data['userTypes']=$userTypeFlag->where('id','<>',1)->get();
        $this->pro_data['notificationTypes']=$notificationType->all();
        return view('admin.notification.index',$this->pro_data);
    }

    public function notificationManagerPost(Request $request){
        restrictToImplementingOffice('abort');
        $syncNotifications=$request->get('notifications');
        $userTypes=UserTypeFlag::all();

        foreach ($userTypes as $userType)
            if(isset($syncNotifications[$userType->id])){
                $userType->NotificationType()->sync($syncNotifications[$userType->id]);
            }else{
                $userType->NotificationType()->detach();
            }
        session()->flash('update_success_info','" Notification Module."');
        return redirect()->back();

    }
    //this should be in implementing office controller
    //but there is authorization restriction

    public function currentProgress(BudgetTopic $budgettopic)
    {
        $budget_topic=$budgettopic->where('monitoring_office_id',Auth::user()->implementing_office_id);
        if($budget_topic->count()==0){
            $monitoringOffices=Auth::user()->implementingOffice->implementingSeesMonitor->pluck('id');
            $budget_topic=$budgettopic->whereIn('monitoring_office_id',$monitoringOffices);
        }
        $this->pro_data['budget_topics'] = $budget_topic->whereStatus(1)->pluck('budget_topic_num','id');
        return view('admin.implementingoffice.currentprogress', $this->pro_data);
    }

    public function currentProgressStore(Request $request)
    {
        $current_progress = new CurrentProgress($request->all());
        $fy = Fiscalyear::where('fy', explode('/', getFiscalyearFromDate($request->get('date')))[0] . '-' . explode('/', getFiscalyearFromDate($request->get('date')))[1])->first();
        $current_progress->fill([
            'implementing_id' => $this->user_info->implementing_office_id,
            'fy_id' => $fy->id,
            'created_by' => $this->user_info->id,
            'updated_by' => $this->user_info->id,
        ]);
        $current_progress->save();
        return redirect()->route('home');
    }


    public function update()
    {
        $source = 'PMIS\\' . request()->get('model');

        $id = request()->get('id');
        $field = request()->get('field');
        $value = request()->get('value');
        $model = new $source;
        $record = $model->find($id);
        $old_record = $model->find($id);
        $record->$field = $value;
        $data['newValue'] = $model->find($record->id)->$field;
        try {
            $record->save();
            if ($old_record->$field == $record->$field) {
                throw (new \Exception('Value unchanged'));
            }
            $data['newValue'] = $model->find($record->id)->$field;
            $data['message'] = ucfirst($field) . ' Successfully Updated';
            return response($data, 200);

        } catch (\Exception $e) {
            $data['message'] = ucfirst($field) . ' ' . $e->getMessage();
            return response($data, 200);
        }
    }

}

