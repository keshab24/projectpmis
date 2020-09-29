<?php namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use PMIS\Traits\DateConverterTrait;

class Procurement extends Model{

    use SearchableTrait;
    use SoftDeletes, DateConverterTrait;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'project_code' => 10, // yo field change garnu parxa
        ],
        'joins'=>[
            'pro_projects'=>['pro_procurements.project_code','pro_projects.id']
        ]
    ];

    protected $table = 'pro_procurements';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['estimated_amount','contract_amount','contract_date','completion_date','project_code','implementing_mode_id','status','con_est_amt_net','est_approved_date','method','bid_does_ready_est','bid_does_ready_act','no_obj_est1','no_obj_act1','call_for_bid_est','call_for_bid_act','bid_open_est','bid_open_act','bid_eval_est','bid_eval_act','no_obj_est2','no_obj_act2','con_sign_est','con_end_est','con_id_div','con_id_web','remarks','verified','design_est_swikrit_miti','bolapatraswikriti','wo_date','contractor','joint_venture','contingency','consultant_id','total_liability'];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'implementing_mode_id' => 'integer',
        'contractor' => 'integer',
        'joint_venture' => 'integer',
        'project_code' => 'integer',
    ];


    public function implementing_mode(){
        return $this->belongsTo('PMIS\ImplementingMode','implementing_mode_id');
    }

    public function project(){
        return $this->belongsTo('PMIS\Project','project_code');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    //This should be deleted ! i think coz i made a change to contractor below this !!
    public function contractors(){
        return $this->belongsToMany('PMIS\Contractor','pro_contractor_pivot','procurement_id','contractor_id')->withTimestamps();
    }

    public function Contractor(){
        return $this->belongsTo('PMIS\Contractor','contractor');
    }


    public function JointVenture(){
        return $this->belongsTo('PMIS\JointVenture','joint_venture');
    }


    public function consultant()
    {
        return $this->belongsTo('PMIS\Contractor', 'consultant_id')->where('type', 2);
    }
    public function getContractDateEngAttribute(){
        if($this->contract_date && $this->contract_date != '0000-00-00')
            return $this->nep_to_eng($this->contract_date)['full_date'];
        return date('Y-m-d');
    }
    
    public function getCompletionDateEngAttribute(){
        if($this->completion_date && $this->completion_date != '0000-00-00')
            return $this->nep_to_eng($this->completion_date)['full_date'];
        return date('Y-m-d');

    }

}