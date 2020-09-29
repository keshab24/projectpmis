<?php
namespace PMIS\Http\Requests;

use PMIS\Http\Requests\Request;

class CreateUserRequest extends Request {

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
                    'email'=>'required',
                    'password'=>'required|min:6',
                    'password_again'=>'required|min:6|same:password',
                    'access'=>'required',
                    'image'=>'sometimes|mimes:jpeg,jpg,png,bmp',
                    'website_id'=>'sometimes|numeric|min:1',
                    'type_flag' => 'required|not_in:1'
                ];
            }
            case 'PATCH':
            case 'PUT':
            {
                return [
                    'name'=>'required|min:2',
                    'email'=>'required',
                    'password'=>'sometimes|min:6',
                    'password_again'=>'sometimes|min:6|same:password',
                    'access'=>'required',
                    'image'=>'sometimes|mimes:jpeg,jpg,png,bmp',
                    'website_id'=>'sometimes|numeric|min:1',
                    'type_flag' => 'required|not_in:1'
                ];
            }
            default:break;
        }

	}

}
