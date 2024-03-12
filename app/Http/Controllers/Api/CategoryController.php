<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->paginate(10);
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();
   
            $validator = Validator::make($input, [
                'name'      => 'required|min:3|string|unique:categories,name',
                'image' => 'required|image|mimes:jpg,png,jpeg|sometimes|nullable'
            ]);
    
            if($validator->fails()){
                return response()->json(['success' => false, 'message' => $validator->errors()]);
            }

            if ($image = $request->file('image')) {
                $ext = $image->extension();
                $file = time() . '.' . $ext;
                $image->storeAs('public/category', $file);
                $input['image'] = $file;
            }

            $create = Category::create($input);
            if (!$create) {
                return response()->json(['success' => false, 'message' => 'Category Not Created..']);
            }
            return response()->json(['success' => true, 'message' => 'Category Created..']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong']);
        }
    }


    public function show(Category $category)
    {
        return response()->json($category);
    }


    public function update(Request $request, Category $category)
    {
        try {
            $input = $request->all();
   
            $validator = Validator::make($input, [
                'name'      => 'min:3|string|unique:categories,name,'. $category->id,
                'image' => 'sometimes|nullable'
            ]);
    
            if($validator->fails()){
                return response()->json(['success' => false, 'message' => $validator->errors()]);
            }

            if ($image = $request->file('image')) {
                $ext = $image->extension();
                $file = time() . '.' . $ext;
                $image->storeAs('public/category', $file);
                $input['image'] = $file;
            }

            $update = $category->update($input);
            if (!$update) {
                return response()->json(['success' => false, 'message' => 'Category Not Updated..']);
            }
            return response()->json(['success' => true, 'message' => 'Category Updated..']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong']);
        }
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['success'=>true, 'message'=> 'Category Deleted']);
    }

    function dashboardCount(){
        $users = User::count();
        $category = Category::count();
        return response()->json(['user_count'=> $users, 'category_count'=> $category]);
    }

    public function activeCategory()
    {
        $totalAppointmentsCount = Category::query()
            ->when(request('status') === '1', function ($query) {
                $query->where('status', 1);
            })
            ->when(request('status') === '0', function ($query) {
                $query->where('status', 0);
            })->count();

        return response()->json([
            'category_count' => $totalAppointmentsCount,
        ]);
    }

    function getUserCount(){
        $totalUsers = User::query()
        ->when(request('status')==='1', function($query){
            $query->where('status', 1);
        })
        ->when(request('status')==='0', function($query){
            $query->where('status', 0);
        })->count();

        return response()->json([
            'user_count'=> $totalUsers
        ]);
    }
}
