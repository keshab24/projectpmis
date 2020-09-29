<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use PMIS\FundStore;
use PMIS\FundTransaction;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\ImplementingOffice;
use PMIS\Payment;
use PMIS\Release;

class ReleaseController extends AdminBaseController {
    protected $pro_data;
    protected $release;
    public function __construct(Release $release){
        parent::__construct();
        $this->release= $release;
    }
    public function index(Release $release, ImplementingOffice $implementingOffice, Payment $payment){
        $this->pro_data['implementingoffices'] = $implementingOffice->whereParent_id(1)->where('id','>',1)->get();
        $this->pro_data['payments'] = $payment->orderBy('release_date','desc')->orderBy('id','desc')->get();
        $this->pro_data['release'] = $release;
        $this->pro_data['trashes_no'] = 0;
        $this->pro_data['releases'] = $this->pro_data['release']->orderBy('id','desc')->simplePaginate(30);
        return view('admin.release.index',$this->pro_data);
    }

    public function create(ImplementingOffice $implementingOffice, District $district){
        $this->pro_data['implementing_offices'] = $implementingOffice->whereLevel(1)->whereIsLastNode(0)->get();

        $this->pro_data['districts'] = add_my_array($district->whereStatus(1)->pluck('name_eng','id'));
        return view('admin.implementingoffice.create', $this->pro_data);
    }

    public function showOfficeRelease(ImplementingOffice $implementingOffice)
    {
        $this->pro_data['implementingoffices'] = ImplementingOffice::whereParent_id(1)->where('id','>',1)->get();
        $this->pro_data['implementing_office'] = $implementingOffice;
        return view('admin.release.show', $this->pro_data);
    }

    public function preview(Payment $payment)
    {
        $this->pro_data['payment'] = $payment;
        return view('admin.release.preview', $this->pro_data);
    }

    public function cheque(Payment $payment)
    {
        $this->pro_data['payment'] = $payment;
        return view('admin.release.cheque', $this->pro_data);
    }

    public function officeRelease(ImplementingOffice $implementingOffice, FundStore $fundStore)
    {
        $this->pro_data['implementingoffices'] = ImplementingOffice::whereParent_id(1)->where('id','>',1)->get();
        $this->pro_data['implementing_office'] = $implementingOffice;
        $this->pro_data['fund_stores'] = $fundStore->pluck('name','id');
        return view('admin.release.release', $this->pro_data);
    }
    public function officeReleasePost(ImplementingOffice $implementingOffice, Request $request)
    {
        $i=0;
        $payment_info = Payment::create([
            'payment_method' => $request->get('payment_method'),
            'release_date' => dateAD($request->get('release_date')),
            'payment_detail' => $request->get('payment_detail'),
            'cheque_no' => $request->get('cheque_no'),
            'fy_id' => $request->session()->get('pro_fiscal_year'),
            'created_by' => $this->user_info->id,
            'updated_by' => $this->user_info->id
        ]);

        $mainAmount = 0;
        foreach($request->get('project_ids') as $project_id){
            $mainAmount += $amount = $request->get('amount')[$i++];
            if($amount >0){
                Release::create([
                    'project_id' => $project_id,
                    'payment_id' => $payment_info->id,
                    'amount' => $amount,
                    'fy_id' => $request->session()->get('pro_fiscal_year'),
                    'created_by' => $this->user_info->id,
                    'updated_by' => $this->user_info->id,
                    'release_date' => dateAD($request->get('release_date'))
                ]);
            }
        }
        if($payment_info){
            $fundTransactionInfo = FundTransaction::create([
                'amount'=>($mainAmount*(-1)),
                'expenditure_topic_id'=>1,
                'type'=>'current',
                'fund_store_id'=>$request->get('fund_store_id'),

                'voucher_no'=>$request->get('cheque_no')!=''?$request->get('cheque_no'):null,
                'deposited_by'=>$request->get('receiver_name')!=''?$request->get('receiver_name'):null,

                'description'=>$request->get('description'),

                'created_by'=> $this->user_info->id,
                'updated_by'=> $this->user_info->id,
            ]);

            if($fundTransactionInfo){
                $fundTransactionInfo->payment()->attach($payment_info->id);
            }
        }
        $i=0;
        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'Release');

        return redirect()->route('release.index',$implementingOffice->id);
    }



    public function store(Request $request){

    }

    public function show(ImplementingOffice $implementingOffice){

    }

    public function edit(Implementingoffice $implementingoffice, District $district){

    }

    public function update(Request $request, Implementingoffice $implementingoffice){

    }



    public function destroy(Implementingoffice $implementingoffice){

    }
}
