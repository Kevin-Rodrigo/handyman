<?php

namespace Modules\ProviderManagement\Http\Requests;

use App\Http\Requests\BaseFormRequest;

use Modules\ProviderManagement\Entities\Provider;

class ProviderUpdateRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $provider_id = Provider::where('uuid',$this->provider)->first('id')->id??null;
        return [
            'first_name' => 'nullable|max:191',
            'last_name' => 'nullable|max:191',
            'name'=>'nullable|max:191',
            'address' => 'nullable|max:1000',
            'email' => 'nullable|unique:providers,email,'.$this->provider.',uuid',
            'owner_email' => 'nullable|unique:users,email,'.$provider_id.',provider_id',
            'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:providers,phone,'.$this->provider.',uuid',
            'owner_phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users,phone,'.$provider_id.',provider_id',
            'password' => 'nullable|min:6',
            'confirm_password' => 'nullable|same:password',
            'identification_image' => 'nullable|array|min:1'
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
