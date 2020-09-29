<?php

namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\BudgetTopic;
use PMIS\ImplementingOffice;

class BudgettopicController extends AdminBaseController
{
    protected $pro_data;
    protected $budgettopic;

    public function __construct(Budgettopic $budgettopic)
    {
        parent::__construct();
        $this->budgettopic = $budgettopic;
        $this->middleware(function ($request, $next) {
            restrictToImplementingOffice('abort');
            if (Auth::user()->implementingOffice->id != 1) {
                $this->budgettopic = $this->budgettopic->whereMonitoringOfficeId(Auth::user()->implementingOffice->id);
            }
            return $next($request);
        });
    }

    public function index(Budgettopic $budgettopic)
    {
        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        $this->pro_data['budgettopics'] = $this->budgettopic;
        if (isset($_GET['search'])) {
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['budgettopics'] = $this->pro_data['budgettopics']->search($_GET['search']);
            $this->pro_data['other_data'] = '&search=' . $_GET['search'];
            if ($this->pro_data['budgettopics']->get()->isEmpty()) {
                $this->pro_data['not_found'] = 'Sorry! could not find your content. Please try with another keywords.';
            }
        }
        if (isset($_GET['orderBy']) && isset($_GET['order'])) {
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order']) == 'asc') ? 'desc' : 'asc';
        }

        if (isset($_GET['trashes'])) {
            $this->pro_data['budgettopics'] = $budgettopic->onlyTrashed();
        }
        $this->pro_data['trashes_no'] = $budgettopic->onlyTrashed()->count();
        $this->pro_data['budgettopics'] = $this->pro_data['budgettopics']->orderBy($this->pro_data['orderBy'], $this->pro_data['order'])->simplePaginate(10);
        return view('admin.budgettopic.index', $this->pro_data);
    }

    public function create(ImplementingOffice $implementingOffice)
    {
        $this->pro_data['monitoringOffice'] = $implementingOffice->whereStatus(1)->where('isMonitoring', 1)->pluck('name', 'id');
        $this->pro_data['priorities'] = ['P1' => 'P1', 'P2' => 'P2', 'P3' => 'P3'];
        return view('admin.budgettopic.create', $this->pro_data);
    }

    public function store(Request $request)
    {
        $status = $request->get('status') == 'on' ? 1 : 0;
        BudgetTopic::create([
            'budget_head' => $request->get('budget_head'),
            'budget_head_eng' => $request->get('budget_head_eng'),
            'budget_topic_num' => $request->get('budget_topic_num'),
            'priority' => $request->get('priority'),
            'budget_head_old' => $request->get('budget_head'),
            'budget_head_eng_old' => $request->get('budget_head_eng'),
            'budget_topic_num_old' => $request->get('budget_topic_num'),
            'priority_old' => $request->get('priority'),
            'monitoring_office_id' => Auth::user()->implementingOffice->id,
            'status' => $status

            /*
             * Old value for new budget topic we insert doesn't make any impact in our app further
             * so i planned to set the equivalent value
             * if it was set null, it would act abnormal..... since sometime we might be looking
             * for old value of new added budget topic, which should give no different result...
             * */
        ]);
        $description = logDescriptionCreate($request->all());
        storeLog(null, $description, 0, 'Budget Topic');
        session()->flash('store_success_info', '" budget topic named ' . $request->get('name') . '"');
        return redirect()->route('budgettopic.create');
    }

    public function show()
    {

    }

    public function edit(Budgettopic $budgettopic, ImplementingOffice $implementingOffice)
    {
        $this->pro_data['monitoringOffice'] = $implementingOffice->whereStatus(1)->where('isMonitoring', 1)->pluck('name', 'id');
        $this->pro_data['budgettopic'] = $budgettopic;
        $this->pro_data['priorities'] = ['P1' => 'P1', 'P2' => 'P2', 'P3' => 'P3'];
        return view('admin.budgettopic.edit', $this->pro_data);
    }

    public function update(Request $request, Budgettopic $budgettopic)
    {
        $status = $request->get('status') == 'on' ? 1 : 0;
        $oldBudgettopic = $budgettopic->toArray();
        $budgettopic->fill([
            'budget_head' => $request->get('budget_head'),
            'budget_head_eng' => $request->get('budget_head_eng'),
            'budget_topic_num' => $request->get('budget_topic_num'),
            'priority' => $request->get('priority'),
            'monitoring_office_id' => Auth::user()->implementingOffice->id,
            'status' => $status
        ])->save();
        $change = logDescriptionUpdate($budgettopic, $oldBudgettopic);
        if ($change != false) {
            storeLog(null, $change, 1, 'Budget Topic');
        }
        session()->flash('update_success_info', '" budgettopic named ' . $request->get('name') . '"');
        return redirect()->route('budgettopic.index');
    }

    public function destroy(Budgettopic $budgettopic)
    {
        $name = $budgettopic->name;
        if (Input::get('hardDelete')) {
            $budgettopic->forceDelete();
        } else {
            $budgettopic->delete();
        }
        storeLog(null, $name, 2, 'Budget Topic');
        session()->flash('delete_success_info', '" budgettopic named ' . $name . '"');
        return redirect()->route('budgettopic.index');
    }
}
