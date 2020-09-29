<?php

namespace PMIS\Http\Requests;

use PMIS\Http\Requests\Request;

class CreateIncomeTopicRequest extends Request
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
                    'name'=>'required|min:3',
                    'code'=>'required'
                    //'expenditure_topic_num'=>'required|numeric'
                ];
            }
            case 'PATCH':
            case 'PUT':
            {
                return [
                    //'expenditure_head'=>'required|min:3|unique:pro_expenditure_topics,id,'.$this->get('id'),
                ];
            }
            default:break;
        }

    }
}