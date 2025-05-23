<?php

namespace App\Http\Controllers;


use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class ImageController extends Controller
{
    public function image()
    {
        return view('welcome');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg,gif|max:2048',
        ]);

        $image = $request->file('image');
        $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

        // Save original image
        $originalPath = public_path('images/original');
        if (!File::exists($originalPath)) {
            File::makeDirectory($originalPath, 0755, true);
        }
        $image->move($originalPath, $imageName);

        // Save cropped image (for now, we generate a scaled version using Intervention)
        $cropFolder = public_path('images/crop-image');
        if (!File::exists($cropFolder)) {
            File::makeDirectory($cropFolder, 0755, true);
        }

        $manager = new ImageManager(new Driver());
        $edited = $manager->read($originalPath . '/' . $imageName);
        $edited->scale(250); // Just an example - scale as needed
        $edited->save($cropFolder . '/' . $imageName);

        // Save image paths to DB
        $imageRecord = Image::create([
            'image' => 'images/original/' . $imageName,
            'crop_image' => 'images/crop-image/' . $imageName,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully!',
            'image_id' => $imageRecord->id,
            'original' => asset($imageRecord->image),
            'cropped' => asset($imageRecord->crop_image),
        ]);
    }
}
