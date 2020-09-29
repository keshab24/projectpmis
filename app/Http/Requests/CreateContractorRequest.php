<?php
namespace PMIS\Http\Requests;

use PMIS\Http\Requests\Request;

class CreateContractorRequest extends Request {

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
//                    'name'=>'required|min:2',
//                    'nep_name'=>'required|min:2',
//                    'address'=>'required',
//                    'authorized_person'=>'required',
//                    'fax'=>'required',
//                    'image'=>'sometimes|mimes:jpeg,jpg,png,bmp',
                ];
            }
            case 'PATCH':
            case 'PUT':
            {
                return [
//                    'name'=>'required|min:2',
//                    'nep_name'=>'required|min:2',
//                    'address'=>'required',
//                    'authorized_person'=>'required',
//                    'fax'=>'required',
//                    'image'=>'sometimes|mimes:jpeg,jpg,png,bmp',
                ];
            }
            default:break;
        }

	}

}
