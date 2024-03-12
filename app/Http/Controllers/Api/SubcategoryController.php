<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubcategoryResource;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubcategoryController extends Controller
{

    public function index()
    {
        $subcategory = Subcategory::with('category')->orderBy('id', 'desc')->paginate(10);
        return SubcategoryResource::collection($subcategory);
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();
   
            $validator = Validator::make($input, [
                'name' => 'required|min:3|string|unique:subcategories,name',
                'cat_id' => 'required|numeric',
                'image' => 'required|image|mimes:jpg,png,jpeg|sometimes|nullable'
            ]);
    
            if($validator->fails()){
                return response()->json(['success' => false, 'message' => $validator->errors()]);
            }

            if ($image = $request->file('image')) {
                $ext = $image->extension();
                $file = time() . '.' . $ext;
                $image->storeAs('public/subcategory', $file);
                $input['image'] = $file;
            }

            $create = Subcategory::create($input);
            if (!$create) {
                return response()->json(['success' => false, 'message' => 'Subcategory Not Created..']);
            }
            return response()->json(['success' => true, 'message' => 'Subcategory Created..']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong']);
        }
    }


    public function show(Subcategory $subcategory)
    {
        return response()->json($subcategory);
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'name' => 'min:3|string|unique:subcategories,name,'. $subcategory->id,
                'cat_id' => 'required|numeric',
                'image' => 'sometimes|nullable'
            ]);
    
            if($validator->fails()){
                return response()->json(['success' => false, 'message' => $validator->errors()]);
            }

            if ($image = $request->file('image')) {
                $ext = $image->extension();
                $file = time() . '.' . $ext;
                $image->storeAs('public/subcategory', $file);
                $input['image'] = $file;
            }

            $update = $subcategory->update($input);
            if (!$update) {
                return response()->json(['success' => false, 'message' => 'Subcategory Not Updated..']);
            }
            return response()->json(['success' => true, 'message' => 'Subcategory Updated..']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong']);
        }
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();
        return response()->json(['success'=>true, 'message'=> 'Subcategory Deleted']);
    }

    public function categoryIdWiseSubcategory(string $id){
        $subcategory = Subcategory::where('cat_id', $id)->get();
        return response()->json($subcategory);
    }
}
