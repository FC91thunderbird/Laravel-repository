<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductAdd extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    function formValidate(){
        $input = $this->all();
    
        if(isset($input['title'])){
            $input['title'] = preg_replace('/[^a-zA-Z0-9\s]/', '', $input['title']);
            $input['title'] = trim($input['title']);
        }
    
        if(isset($input['description'])){
            $input['description'] = preg_replace('/[^a-zA-Z0-9\s]/','', $input['description']);
            $input['description'] = trim($input['description']);
        }
    
        if(isset($input['price'])){
            $input['price'] = preg_replace('/[^0-9\s]/','', $input['price']);
            $input['price'] = trim($input['price']);
        }
        if(isset($input['buy_price'])){
            $input['buy_price'] = preg_replace('/[^0-9\s]/','', $input['buy_price']);
            $input['buy_price'] = trim($input['buy_price']);
        }
        if(isset($input['sku'])){
            $input['sku'] = preg_replace('/[^0-9\s]/','', $input['sku']);
            $input['sku'] = trim($input['sku']);
        }
    
        return $this->replace($input);
       }


    public function rules(): array
    {
        return [
            'title' => 'required|min:6|string|unique:products,title',
            'category' => 'required|numeric', 
            'subcategory' => 'required|numeric', 
            'buy_price' => 'required|numeric', 
            'price' => 'required|numeric', 
            'size' => 'required|array', 
            'color' => 'required|array', 
            'description' => 'required|string',
            // 'image' => 'required|image|mimes:jpeg,jpg,png',
        ];
    }
}
