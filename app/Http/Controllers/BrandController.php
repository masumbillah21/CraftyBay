<?php

namespace App\Http\Controllers;

use App\Helper\ImageHelper;
use Exception;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helper\CraftyJsonResponse;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $brands = Brand::all();

        return CraftyJsonResponse::response('success','Request Successful', $brands);
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

            $brand = Brand::create([
                'name' => $request->input('name'),
                'logo' => $logoPath,
                'slug' => $request->input('name')
            ]);

            return CraftyJsonResponse::response('success','Request Successful', $brand);

        }catch(Exception $exception){
            return CraftyJsonResponse::response('error', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($brand): JsonResponse
    {
        try{

            $singleBrand = Brand::findByIdOrSlug($brand)->first();

            if($singleBrand){
                return CraftyJsonResponse::response('success','Request Successful', $singleBrand);
            }

            return CraftyJsonResponse::response('error','No data found.');

            

        }catch(Exception $exception){
            return CraftyJsonResponse::response('error', $exception->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $brand)
    {
        
        try{

            $brand = Brand::findByIdOrSlug($brand)->first();

            if(!$brand){
                return CraftyJsonResponse::response('error','No data found.');
            }
            
            $request->validate([
                'name'=>'nullable|max:20|string',
                'logo'=>'nullable',
            ]);

            if ($request->has('logo')) {
                $logoData = $request->input('logo');
                $logoPath = ImageHelper::imageUpload($logoData, $request->input('name'));
                ImageHelper::imageDelete($brand->logo);
            } else {
                $logoPath = null;
            }


            $brand->logo = $logoPath ?? $brand->logo;
            $brand->name = $request->name ?? $brand->name;
            $brand->slug = $request->name ?? $brand->slug;

            $updated = $brand->update();

            if($updated){
                return CraftyJsonResponse::response('success','Brand Updated Successful', $brand->fresh());
            }
            
            return CraftyJsonResponse::response('error','Brand Failed To Update', $brand);
            
        }catch(Exception $exception){
            return CraftyJsonResponse::response('error', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($brand)
    {
        try{

            $brand = Brand::findByIdOrSlug($brand);

            if(!$brand){
                return CraftyJsonResponse::response('error','No data found.');
            }

            ImageHelper::imageDelete($brand->first()->logo);

            $brand->delete();

            return CraftyJsonResponse::response('success','Brand Deleted Successful');
            
        }catch(Exception $exception){
            return CraftyJsonResponse::response('error', $exception->getMessage());
        }
    }
}
