<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicket extends FormRequest
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
        return [
            'position' => 'sometimes|required|numeric',
            'fName' => 'sometimes|required|string|min:2',
            'mName' => 'sometimes|required|string|min:2',
            'lName' => 'sometimes|required|string|min:2',
            'store' => 'sometimes|required|numeric',

            'caller_id' =>  'sometimes|required|numeric',
            'contact_id' =>  'sometimes|required|numeric',
            'fixed_by' =>  'sometimes|required|numeric',
            'subject' =>  'sometimes|required|string|min:10',
            'details' =>  'sometimes|required|string|min:10',
            'attachments' => 'sometimes|nullable',
            'type' =>  'sometimes|required|numeric',
            'priority' => 'sometimes|required|numeric',
            'category' => 'sometimes|required|numeric',
            'catA' =>  'sometimes|required|numeric',
            'catB' =>  'sometimes|nullable|numeric',
            'catC' =>  'sometimes|nullable|numeric',
            'group' =>  'sometimes|nullable|numeric',
            'assignee' =>'sometimes|nullable',
            'incident.subject' =>  'sometimes|required|string|min:10',
            'incident.details' =>  'sometimes|required|string|min:10',

        ];
    }


    public function attributes()
    {
        return [
            'catA' => 'Category A',
            'catB' => 'Category B',
            'incident.subject' => 'subject',
            'incident.details' => 'details'
        ];
    }
}
