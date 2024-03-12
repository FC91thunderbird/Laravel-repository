<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryAdd;
use App\Http\Requests\Category\CategoryUpdate;
use App\Http\Resources\Category\CategoryList;
use App\Http\Resources\Category\CategoryResource;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository();
    }

    public function index(Request $request)
    {
        $per_page = $request->get('per_page', 10);
        $search = $request->get('search', '');

        $category = $this->categoryRepository->getList($search, $per_page);

        if(empty($category)){
            return $this->response(false, [], 'Category Not Found');
        }

        $data = [
            'categories' => new CategoryList($category),
        ];

        return $this->response(true, $data, 'categories');
    }


    public function store(CategoryAdd $request)
    {
        $validatedData = $request->validated();

       if($image = $request->file('image')){
           $imageName = $image->getClientOriginalName();
           $imagePath = $request->file('image')->storeAs('category', $imageName, 'public');
           $validatedData['image'] = $imagePath;
       }

        // Handle image upload
        // if ($request->hasFile('image')) {
        //     $imagePath = $request->file('image')->store('category', 'public');
        //     $validatedData['image'] = $imagePath;
        // }

        $category = $this->categoryRepository->store($validatedData);

        if(empty($category)){
            return $this->response(false, [], 'Failed to create Category');
        }

        $data = [
            'category' => $category,
        ];
        
        return $this->response(true, $data, 'Category Created..', 201);
    }

    public function show(string $id)
    {
        $category = $this->categoryRepository->getById($id);

        if(empty($category)){
            return $this->response(false, [], 'Category Not Found');
        }

        $data = [
            'category' => new CategoryResource($category),
        ];

        return $this->response(true, $data, 'Category Fetch');
    }

 
    public function update(CategoryUpdate $request, string $id)
    {
        $validatedData = $request->validated();
        if($image = $request->file('image')){
            $imageName = $image->getClientOriginalName();
            $imagePath = $request->file('image')->storeAs('category', $imageName, 'public');
            $validatedData['image'] = $imagePath;
        }

        $category = $this->categoryRepository->update($id, $validatedData);

        if(empty($category)){
            return $this->response(false, [], 'Failed to update category');
        }

        $data = [
            'category' => $category,
        ];

        return $this->response(true, $data, 'Category Updated Success');
    }

    public function destroy(string $id)
    {
        $category = $this->categoryRepository->delete($id);

        if(empty($category)){
            return $this->response(false, [], 'Failed to delete Cateory');
        }

        $data = [
            'category' => $category
        ];

        return $this->response(true, $data, 'Category Deleted');
    }
}
