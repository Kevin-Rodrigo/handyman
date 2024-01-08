<?php

namespace Modules\ProviderManagement\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule; 

class ProviderStoreRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'name'=>'required',
            'address' => 'required',
            'email' => 'required|unique:providers',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:providers',
            'owner_phone' => ['required','regex:/^([0-9\s\-\+\(\)]*)$/','min:10',Rule::unique('users','phone')->where('user_type', 'provider-admin')],
            'owner_email' => ['required','email',Rule::unique('users','email')->where('user_type', 'provider-admin')],
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'zone_id' => 'required',
            'logo' => 'required',
            'identification_number'=>'required',
            'identification_type' => 'required',
            'identification_image' => 'required|array|min:1'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
