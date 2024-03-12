<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductAdd;
use App\Http\Requests\ProductUpdate;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $categoryId = request('category');
        $sizes = ['small', 'medium', 'large'];
        $colors = ['black', 'white', 'yellow'];

        $query = Product::with('category', 'subcategory')->orderBy('id', 'desc');
       
        if ($categoryId) {
            if(in_array($categoryId, $sizes)){
                $query->whereJsonContains('size', $categoryId);
            }elseif(in_array($categoryId, $colors)) {
                $query->whereJsonContains('color', $categoryId);
            }else{
                $query->whereHas('category', function ($query) use ($categoryId) {
                    $query->where('id', $categoryId);
                });
            }
        }

        $products = $query->paginate(10);

        // $products = Product::with('category', 'subcategory')->orderBy('id', 'desc')->paginate(10);
        return ProductResource::collection($products);
    }

    public function store(ProductAdd $request)
    {
        try {
            $input = $request->validated();

            $input['size'] = explode(',', $input['size'][0]);
            $input['color'] = explode(',', $input['color'][0]);

            if ($image = $request->file('image')) {
                $ext = $image->extension();
                $file = time() . '.' . $ext;
                $image->storeAs('public/product', $file);
                $input['image'] = $file;
            }

            $create = Product::create($input);
            if (!$create) {
                return response()->json(['success' => false, 'message' => 'Product Not Created..'], 422);
            }
            return response()->json(['success' => true, 'message' => 'Product Created..'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong..'], 500);
        }
    }


    public function show(Product $product)
    {
        return response()->json($product);
    }

    public function update(ProductUpdate $request, Product $product)
    {
        try {
            $input = $request->validated();

            $input['size'] = explode(',', $input['size'][0]);
            $input['color'] = explode(',', $input['color'][0]);

            if ($image = $request->file('image')) {
                $ext = $image->extension();
                $file = time() . '.' . $ext;
                $image->storeAs('public/product', $file);
                $input['image'] = $file;
            }

            $update = $product->update($input);
            if (!$update) {
                return response()->json(['success' => false, 'message' => 'Product Not Updated..'], 422);
            }
            return response()->json(['success' => true, 'message' => 'Product Update successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error Update User..'], 500);
        }
    }

    public function destroy(Product $product)
    {
        if (!empty($product->image)) {
            Storage::disk('public/product/')->delete($product->image);
        }

        $product->delete();
        return response()->json(['success' => true, 'message' => 'Product Deleted successfully'], 200);
    }
}
