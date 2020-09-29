<?php namespace ProNews\Http\Requests;

use ProNews\Http\Requests\Request;

class CreateMenuRequest extends Request {

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
            'name'=>'required|min:2',
            'name_nep'=>'required|min:2',
            'category_id'=>'required|numeric',
            'level'=>'required|numeric',
            'order'=>'required|numeric|min:0'
		];
	}

}
