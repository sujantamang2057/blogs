<?php

namespace App\Http\Controllers;

use App\Http\Requests\blogcategoryRequest;
use App\Models\blog_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        // Fetch post categories with pagination
        $blogCategoriestable = blog_category::orderBy('id', 'asc')->paginate(10); // Adjust the number per page as needed

        // Return the view with the paginated data
        return view('admin.blog_category.index', compact('blogCategoriestable'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $blogCategories = blog_category::all();

        return view('admin.blog_category.create', compact('blogCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(blogcategoryRequest $request)
    {
        // Store a new blog category in the database

        //

        // here we will insert product in db
        $blogCategory = new blog_category;
        $blogCategory->title = $request->title;
        $blogCategory->slug = Str::slug($request->title);

        $blogCategory->parent_id = $request->parent_id;
        $blogCategory->status = $request->has('status') ? 1 : 0;

        // Save the blog category

        // If an image is uploaded, save it to the uploads folder and update the blog category
        if ($request->input('image')) {
            $imagePath = $request->input('image');

            $filename = basename($imagePath);

            // Define paths
            $originalPath = 'images/'.$filename;

            $resizedPath = 'images/resized/'.$filename;

            // Move the file from 'temporay place' to 'original path'
            Storage::disk('public')->move($imagePath, $originalPath);

            // Resize the image using Intervention Image
            $resizedImage = Image::make(storage_path('app/public/'.$originalPath))->resize(300, 300);

            // Store the resized image
            Storage::disk('public')->put($resizedPath, (string) $resizedImage->encode());

            // Save the new image path in the database
            $blogCategory->image = $originalPath;
        }

        $blogCategory->save();

        // Redirect to the blog category index with a success message
        return redirect()->route('category.index')->with('success', 'category added successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $blogCategory = blog_category::findorfail($id);

        return view('admin.blog_category.show', compact('blogCategory'));
        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //find by id
        $blogCategory = blog_category::findOrFail($id);
        // Fetch all categories for the parent category select dropdown
        $categories = blog_category::all();

        return view('admin.blog_category.edit', compact('blogCategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(blogcategoryRequest $request, string $id)
    {
        //

        $blogCategory = blog_category::findOrFail($id);

        if ($request->image != '') {
            $rules['image'] = 'image';
        }

        // here we will update product
        $blogCategory->title = $request->title;
        $blogCategory->slug = Str::slug($request->title);

        $blogCategory->parent_id = $request->parent_id;
        $blogCategory->status = $request->has('status') ? 1 : 0;

        // Save the blog category

        if ($request->input('image')) {
            // Delete old images if they exist
            if ($blogCategory->image) {
                File::delete(public_path('storage/'.$blogCategory->image));
                File::delete(public_path('storage/images/resized/'.basename($blogCategory->image)));
            }

            $imagePath = $request->input('image');
            $filename = basename($imagePath);

            // Define paths
            $originalPath = 'images/'.$filename;
            $resizedPath = 'images/resized/'.$filename;

            // Move the file from 'tmp' to 'images'
            Storage::disk('public')->move($imagePath, $originalPath);

            // Resize the image using Intervention Image
            $resizedImage = Image::make(storage_path('app/public/'.$originalPath))->resize(300, 300);

            // Store the resized image
            Storage::disk('public')->put($resizedPath, (string) $resizedImage->encode());

            // Save the new image path in the database
            $blogCategory->image = $originalPath;
        }
        $blogCategory->save();

        return redirect()->route('category.index')->with('success', 'blog category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $blogCategory = blog_category::findOrFail($id);
        //deleting the image from file
        File::delete(public_path('uploads/'.$blogCategory->image));

        $blogCategory->delete();

        return redirect()->route('category.index')->with('success', 'blog category deleted successfully.');

    }

    public function updateStatus(Request $request)
    {

        $blogcategory = blog_category::find($request->id);

        if ($blogcategory) {
            $blogcategory->status = $request->status;

            $blogcategory->save();

            return response()->json(['success' => true, 'status' => $blogcategory->status]);
        }

        return response()->json(['success' => false]);
    }
}
