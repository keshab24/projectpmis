<?php
namespace PMIS\Http\Requests;

use PMIS\Http\Requests\Request;

class CreateEmployeeRequest extends Request {

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
        switch($this->method()) {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'name'=>'required|min:2',
                    'district'=>'required',
                    'address'=>'required',
                    'date_of_birth'=>'required',
                    'date_of_join'=>'required',
                    'designation_id'=>'required',
                    'image'=>'sometimes|mimes:jpeg,jpg,png,bmp',
                ];
            }
            case 'PATCH':
            case 'PUT':
            {
                return [
                    'name'=>'required|min:2',
                    'district'=>'required',
                    'address'=>'required',
                    'date_of_birth'=>'required',
                    'date_of_join'=>'required',
                    'designation_id'=>'required',
                    'image'=>'sometimes|mimes:jpeg,jpg,png,bmp',
                ];
            }
            default:break;
        }

	}

}
