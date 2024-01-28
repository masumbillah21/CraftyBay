<?php 
namespace App\Helper;

use Illuminate\Support\Facades\Storage;

class ImageHelper{
    public static function imageUpload($encodedImage, $imageName): string{
        $logoData = $encodedImage;
        $logoData = str_replace('data:image/png;base64,', '', $logoData);
        $logoData = str_replace(' ', '+', $logoData);
        $logoImage = base64_decode($logoData);
        $logoPath = 'image/'. strtolower($imageName) . time() . '.png';
        //file_put_contents(public_path($logoPath), $logoImage);
        Storage::disk('public')->put($logoPath, $logoImage);

        return $logoPath;
    }

    public static function imageDelete($image) : bool {

        if (file_exists(public_path('storage/'.$image)) && !empty($image) && !is_null($image)) {
            unlink(public_path('storage/'.$image));
            return true;
        }
        return false;
    }
}