<?php
namespace PMIS\Http\Requests;

use PMIS\Http\Requests\Request;

class CreateTimeExtensionRequest extends Request {

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
        return [
            'start_date'=>'required',
            'end_date'=>'required',
            'extended_on'=>'required',
        ];
	}

}
