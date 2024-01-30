<?php 
namespace App\Helper;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ImageHelper{
    public static function imageUpload($encodedImage, $imageName): string{
        $logoData = $encodedImage;
        $logoData = str_replace('data:image/png;base64,', '', $logoData);
        $logoData = str_replace(' ', '+', $logoData);
        $logoImage = base64_decode($logoData);
        $logoPath = 'image/'. Str::slug($imageName) . time() . '.jpg';
        //file_put_contents(public_path($logoPath), $logoImage);
        Storage::disk('public')->put($logoPath, $logoImage);

        return $logoPath;
    }

    public static function imageDelete($image) : bool {

        if (Storage::disk('public')->exists($image) && !empty($image) && !is_null($image)) {
            Storage::disk('public')->delete($image);
            return true;
        }
        return false;
    }
}