<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Brand;
use App\Models\Category;
use App\Helper\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helper\CraftyJsonResponse;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = Category::all();

        return CraftyJsonResponse::response('success','Request Successful', $categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try{
            $request->validate([
                'name'=>'required|max:20|string',
                'logo'=>'nullable',
            ]);

            if ($request->has('logo')) {
                $logoData = $request->input('logo');
                $logoPath = ImageHelper::imageUpload($logoData, $request->input('name'));
            } else {
                $logoPath = null;
            }

            $category = Category::create([
                'name' => $request->input('name'),
                'logo' => $logoPath,
                'slug' => $request->input('name')
            ]);

            return CraftyJsonResponse::response('success','Request Successful', $category);

        }catch(Exception $exception){
            return CraftyJsonResponse::response('error', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($idOrSlug): JsonResponse
    {
        try{

            $category = Category::findByIdOrSlug($idOrSlug)->first();

            if($category){
                return CraftyJsonResponse::response('success','Request Successful', $category);
            }

            return CraftyJsonResponse::response('error','No data found.');

            

        }catch(Exception $exception){
            return CraftyJsonResponse::response('error', $exception->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        
        try{
            
            $request->validate([
                'name'=>'nullable|max:20|string',
                'logo'=>'nullable',
            ]);

            if ($request->has('logo')) {
                $logoData = $request->input('logo');
                $logoPath = ImageHelper::imageUpload($logoData, $request->input('name'));
                ImageHelper::imageDelete($category->logo);
            } else {
                $logoPath = null;
            }


            $category->logo = $logoPath ?? $category->logo;
            $category->name = $request->name ?? $category->name;
            $category->slug = $request->name ?? $category->slug;

            $updated = $category->update();

            if($updated){
                return CraftyJsonResponse::response('success','Category Updated Successful', $category->fresh());
            }
            
            return CraftyJsonResponse::response('error','Category Failed To Update', $category);
            
        }catch(Exception $exception){
            return CraftyJsonResponse::response('error', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idOrSlug)
    {
        try{

            $category = Category::findByIdOrSlug($idOrSlug)->noWhere('is_default', 1);

            if(!$category){
                return CraftyJsonResponse::response('error','No data found.');
            }

            ImageHelper::imageDelete($category->first()->logo);

            $category->delete();

            return CraftyJsonResponse::response('success','Category Deleted Successful');
            
        }catch(Exception $exception){
            return CraftyJsonResponse::response('error', $exception->getMessage());
        }
    }
}
