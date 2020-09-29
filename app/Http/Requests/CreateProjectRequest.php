<?php
namespace PMIS\Http\Requests;

use Illuminate\Support\Facades\Auth;
use PMIS\Http\Requests\Request;

class CreateProjectRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{

        $monitoring_office='';
        if(Auth::User()->implementingOffice->isMonitoring==0){
            $monitoring_office='not_in:0';
        }

        switch($this->method()) {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'district_id'=>'required|not_in:0',
                    'project_code'=>'required',
                    'name'=>'required|unique:pro_projects',
                    'name_eng'=>'required',
                    'budget_topic_id'=>'Required|not_in:0',
                    'expenditure_topic_id'=>'required|not_in:0',
                    'construction_type_id'=>'required|not_in:0',
                    'monitoring_office_id'=>'required|'.$monitoring_office,
                    'implementing_office_id'=>'required|not_in:0',
                    'project_group_id'=>'required|not_in:0',
                    'construction_located_area_id'=>'required|not_in:0',
                    'nature_of_project_id'=>'required|not_in:0',
                    'estimated_amount'=>'required',
                ];
            }
            case 'PATCH':
            case 'PUT':
            {
                return [
                    'district_id'=>'required|not_in:0',
                    'project_code'=>'required',
                    'name' => 'required|unique:pro_projects,name,'.$this->project->id,
                    'name_eng' => 'required',
                    //'budget_topic_id'=>'Required|not_in:0',
                    'expenditure_topic_id'=>'required|not_in:0',
                    //'construction_type_id'=>'required|not_in:0',
                    'monitoring_office_id'=>'required|'.$monitoring_office,
                    'implementing_office_id'=>'required|not_in:0',
                    //'project_group_id'=>'required|not_in:0',
                    //'construction_located_area_id'=>'required|not_in:0',
                    'nature_of_project_id'=>'required|not_in:0',
                ];
            }
            default:break;
        }

	}

}
