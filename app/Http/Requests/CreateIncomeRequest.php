<?php

namespace PMIS\Http\Requests;

use PMIS\Http\Requests\Request;

class CreateIncomeRequest extends Request
{
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
                    'income_topic_id'=>'required',
                    'amount'=>'required',
                    'description'=>'required',
                    'receiver_name'=>'required'
                    //'expenditure_topic_num'=>'required|numeric'
                ];
            }
            case 'PATCH':
            case 'PUT':
            {
                return [
                ];
            }
            default:break;
        }

    }
}