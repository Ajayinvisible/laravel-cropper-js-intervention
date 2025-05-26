<?php

namespace App\Http\Controllers;


use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageController extends Controller
{
    public function image()
    {
        $images = Image::orderBy('created_at', 'desc')->get();
        return view('welcome', [
            'images' => $images
        ]);
    }

    public function upload(Request $request)
    {
        // Validate input
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg,gif|max:2048',
            'cropped_image' => 'required|file|mimes:jpeg,png,jpg',
        ]);

        // Handle original image
        $original = $request->file('image');
        $originalName = time() . '_' . Str::random(10) . '.' . $original->getClientOriginalExtension();

        $originalPath = public_path('images/original');
        if (!File::exists($originalPath)) {
            File::makeDirectory($originalPath, 0755, true);
        }
        $original->move($originalPath, $originalName);

        // Handle cropped image
        $cropped = $request->file('cropped_image');
        $croppedName = 'cropped_' . $originalName;

        $croppedPath = public_path('images/crop-image');
        if (!File::exists($croppedPath)) {
            File::makeDirectory($croppedPath, 0755, true);
        }
        $cropped->move($croppedPath, $croppedName);

        // Generate passport-sized image (600x600)
        $passportName = 'passport_' . $originalName;

        $imageManager = new ImageManager(new Driver());
        $ppCroppedImage = $imageManager->read($croppedPath . '/' . $croppedName);
        $ppCroppedImage->resize(600, 600);
        $ppCroppedImage->save($croppedPath . '/' . $passportName);

        // Save file paths in the database
        $image = Image::create([
            'image' => 'images/original/' . $originalName,
            'crop_image' => 'images/crop-image/' . $croppedName,
            'passport_image' => 'images/crop-image/' . $passportName,
        ]);

        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully!',
            'original' => asset('images/original/' . $originalName),
            'cropped' => asset('images/crop-image/' . $croppedName),
            'passport' => asset('images/crop-image/' . $passportName),
            'image_id' => $image->id,
        ]);
    }

    public function editImage($id)
    {
        $image = Image::findOrFail($id);
        return view('edit', compact('image'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'image_id' => 'required|exists:images,id',
            'cropped_image' => 'required|string',
        ]);

        $image = Image::findOrFail($request->image_id);

        // Extract base64 string
        $croppedBase64 = $request->input('cropped_image');
        preg_match("/^data:image\/(\w+);base64,/", $croppedBase64, $type);

        if (!$type || !isset($type[1])) {
            return response()->json(['success' => false, 'message' => 'Invalid image type.'], 422);
        }

        $ext = strtolower($type[1]);
        $croppedBase64 = substr($croppedBase64, strpos($croppedBase64, ',') + 1);
        $croppedBase64 = base64_decode($croppedBase64);

        if ($croppedBase64 === false) {
            return response()->json(['success' => false, 'message' => 'Base64 decoding failed.'], 422);
        }

        // Define paths and names
        $timestamp = time();
        $unique = Str::random(10);
        $croppedName = 'cropped_' . $timestamp . '_' . $unique . '.' . $ext;
        $passportName = 'passport_' . $timestamp . '_' . $unique . '.' . $ext;
        $croppedPath = public_path('images/crop-image');

        if (!File::exists($croppedPath)) {
            File::makeDirectory($croppedPath, 0755, true);
        }

        // Delete old images if they exist
        if ($image->crop_image && File::exists(public_path($image->crop_image))) {
            File::delete(public_path($image->crop_image));
        }
        if ($image->passport_image && File::exists(public_path($image->passport_image))) {
            File::delete(public_path($image->passport_image));
        }

        // Save new cropped image
        file_put_contents($croppedPath . '/' . $croppedName, $croppedBase64);

        // Generate passport image (600x600)
        $imageManager = new ImageManager(new Driver());
        $passportImage = $imageManager->read($croppedPath . '/' . $croppedName);
        $passportImage->resize(600, 600);
        $passportImage->save($croppedPath . '/' . $passportName);

        // Update DB
        $image->update([
            'crop_image' => 'images/crop-image/' . $croppedName,
            'passport_image' => 'images/crop-image/' . $passportName,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cropped and passport images updated successfully!',
            'cropped' => asset('images/crop-image/' . $croppedName),
            'passport' => asset('images/crop-image/' . $passportName),
        ]);
    }

    public function deleteImage($id)
    {
        $image = Image::findOrFail($id);

        // Delete original image
        $originalPath = public_path($image->image);
        if (File::exists($originalPath)) {
            File::delete($originalPath);
        }

        // Delete cropped image
        $croppedPath = public_path($image->crop_image);
        if (File::exists($croppedPath)) {
            File::delete($croppedPath);
        }

        // Delete from database
        $image->delete();

        return redirect()->route('image')->with('success', 'Image deleted successfully!');
    }
}
