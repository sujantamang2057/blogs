<?php

namespace App\Http\Controllers;

use App\DataTables\AlbumDataTable;
use App\Models\Album;
use App\Models\Albumimages;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AlbumDataTable $dataTable)
    {
        return $dataTable->render('admin.album.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        //
        return view('admin.album.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // Create a new Album
        $albums = new Album;
        $albums->title = $request->title;
        $albums->slug = $request->slug;
        $albums->date = $request->date ? Carbon::parse($request->date) : $albums->date;
        $albums->description = $request->description;
        $albums->status = $request->has('status') ? 1 : 0;

        // Save the album first before associating the images
        $albums->save();

        // Handle multiple images
        if ($request->has('image')) {
            // Iterate over the uploaded images
            foreach ($request->image as $imagePath) {
                $filename = basename($imagePath);

                // Define paths
                $originalPath = 'images/'.$filename;
                $resized100Path = 'images/resized/100px_'.$filename;
                $resized800Path = 'images/resized/800px_'.$filename;

                // Move the file from 'tmp' to 'images'
                Storage::disk('public')->move($imagePath, $originalPath);

                // Resize the image using Intervention Image

                // 100px width image
                $resized100Image = Image::make(storage_path('app/public/'.$originalPath))
                    ->resize(100, null, function ($constraint) {
                        $constraint->aspectRatio(); // Keep aspect ratio
                        $constraint->upsize(); // Prevent upsizing
                    });
                // Save resized image to disk
                Storage::disk('public')->put($resized100Path, (string) $resized100Image->encode());

                // 800px width image
                $resized800Image = Image::make(storage_path('app/public/'.$originalPath))
                    ->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio(); // Keep aspect ratio
                        $constraint->upsize(); // Prevent upsizing
                    });
                // Save resized image to disk
                Storage::disk('public')->put($resized800Path, (string) $resized800Image->encode());

                // Save each image record in the database (assuming you have an `AlbumImage` model)
                $image = new Albumimages; // Use the renamed model
                $image->album_id = $albums->id; // Associate the image with the album
                $image->image_name = $originalPath;
                $image->cover_image = $originalPath; // Save original image path
                $image->status = 1; // Or any other status you'd like to assign
                $image->created_by = Auth::user()->name;
                $image->save();
            }
        }

        return redirect()->route('Album.index', $albums->id)->with('success', 'Album created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Album $album)
    {
        //

        return view('admin.album.show', compact('album'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Album $album)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Album $album)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album)
    {
        //
    }

    public function multipleUpload(Request $request)
    {
        $paths = [];

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $paths[] = $file->store('tmp', 'public');
            }

            return response()->json(['paths' => $paths]);
        }

        return response()->json(['error' => 'No files uploaded'], 400);
    }
}
