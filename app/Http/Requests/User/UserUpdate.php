<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdate extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    function prepareForValidation(){
        $input = $this->all();
    
        if (isset($input['name'])) {
            $input['name'] = preg_replace('/[^a-zA-Z0-9\s]/', '', $input['name']);
            $input['name'] = trim($input['name']);
        }
        if (isset($input['username'])) {
            $input['username'] = preg_replace('/[^a-zA-Z0-9\s]/', '', $input['username']);
            $input['username'] = trim($input['username']);
        }
    
        if (isset($input['email'])) {
            $input['email'] = trim($input['email']);
        }
    
        $this->replace($input);
    }

    public function rules(): array
    {
        return [
            'name'=>'string',
            'username'=>'string',
            'email'=> 'email',
            'role_id'=> 'numeric',
        ];
    }
}
