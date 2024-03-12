<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdate extends FormRequest
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
    
        $this->replace($input);
    }

    public function rules(): array
    {
        return [
            'name'=>'string|unique:categories,name,' . $this->route('category'),
            'image' => 'image|mimes:jpg,png,jpeg'
        ];
    }
}
