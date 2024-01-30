<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use App\Models\Brand;
use App\Models\Product;
use App\Helper\ImageHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helper\CraftyJsonResponse;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products =  Product::with('brand','categories')->get();
        return CraftyJsonResponse::response('success','Request Success',$products);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $request->validate([
                'name' => 'required|string|max:255',
                'shot_desc' => 'nullable',
                'desc' => 'nullable',
                'remark' => 'nullable',
                'reqular_price' => 'required',
                'sales_price' => 'nullable',
                'featured_image' => 'required',
                'gallery_images' => 'nullable|array',
                'weight' => 'nullable|numeric',
                'length' => 'nullable|numeric',
                'width' => 'nullable|numeric',
                'height' => 'nullable|numeric',
                'stock' => 'nullable|numeric',
                'is_active' => 'nullable|boolean',
                'is_featured' => 'nullable|boolean',
                'brand_id' => 'nullable|exists:brands,id',
                'category_id' => 'nullable|exists:categories,id',
            ]);

            $request->merge(['slug' => Str::slug($request->input('name'))]);

            $product = Product::create($request->all());

            $product->categories()->attach($request->category_id ?? 1);

            DB::commit();

            return CraftyJsonResponse::response('success','Request Success',$product);

        }catch(Exception $e){
            DB::rollBack();
            return CraftyJsonResponse::response('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function byBrand(Brand $brand)
    {
        $proucuts =  $brand->products;

        return CraftyJsonResponse::response('success','Request Successful', $proucuts);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function byCategory(Category $category)
    {
        $proucuts =  $category->products;

        return CraftyJsonResponse::response('success','Request Successful', $proucuts);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $idOrSlug)
    {
        try{

            $product = Product::findByIdOrSlug($idOrSlug)->with('brand', 'categories')->first();

            if($product){
                return CraftyJsonResponse::response('success','Request Successful', $product);
            }

            return CraftyJsonResponse::response('error','No data found.');

            

        }catch(Exception $exception){
            return CraftyJsonResponse::response('error', $exception->getMessage());
        }
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        DB::beginTransaction();
        try{
            $request->validate([
                'name' => 'nullable|string|max:255',
                'shot_desc' => 'nullable',
                'desc' => 'nullable',
                'remark' => 'nullable',
                'reqular_price' => 'required',
                'sales_price' => 'nullable',
                'featured_image' => 'nullable',
                'gallery_images' => 'nullable|array',
                'weight' => 'nullable|numeric',
                'length' => 'nullable|numeric',
                'width' => 'nullable|numeric',
                'height' => 'nullable|numeric',
                'stock' => 'nullable|numeric',
                'is_active' => 'nullable|boolean',
                'is_featured' => 'nullable|boolean',
                'brand_id' => 'nullable|exists:brands,id',
                'category_id' => 'nullable|exists:categories,id',
            ]);

            $product->update($request->all());

            $product->categories()->sync($request->category_id ?? 1);

            DB::commit();

            return CraftyJsonResponse::response('success','Request Success',$product->fresh());

        }catch(Exception $e){
            DB::rollBack();
            return CraftyJsonResponse::response('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try{
            if(!$product){
                return CraftyJsonResponse::response('error','No data found.');
            }

            ImageHelper::imageDelete($product->featured_image);
            $product->categories()->detach();
            $product->delete();

            return CraftyJsonResponse::response('success','Product Deleted Successful');
            
        }catch(Exception $exception){
            return CraftyJsonResponse::response('error', $exception->getMessage());
        }
    }
}
