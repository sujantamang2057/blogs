<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Models\blog;
use App\Models\blog_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\storage;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blog = blog::paginate(10); // Adjust the number per page as needed

        // Return the view with the paginated data
        return view('admin.blog.index', compact('blog'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $blogCategories = blog_category::all();

        return view('admin.blog.create', compact('blogCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogRequest $request)
    {
        //
        // dd($request->image);
        $blogCategory = new blog;
        $blogCategory->title = $request->title;
        $blogCategory->description = $request->description;

        $blogCategory->blog_category_id = $request->blog_category_id;
        $blogCategory->status = $request->has('status') ? 1 : 0;

        // Save the blog category
        // If an image is uploaded, save it to the uploads folder and update the blog category
        if ($request->input('image')) {
            $imagePath = $request->input('image');

            $filename = basename($imagePath);

            $newPath = 'images/'.$filename;

            // Move the file from 'tmp' to 'images'
            Storage::disk('public')->move($imagePath, $newPath);

            // Save the new image path in the database
            $blogCategory->image = $newPath;

        }
        $blogCategory->save();

        // Redirect to the blog category index with a success message
        return redirect()->route('blog.index')->with('success', 'category post added successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $blog = blog::findorfail($id);

        return view('admin.blog.show', compact('blog'));
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $blogCategory = blog::findOrFail($id);
        // Fetch all categories for the parent category select dropdown
        $categories = blog_category::all();

        return view('admin.blog.edit', compact('blogCategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRequest $request, string $id)
    {
        $blogCategory = blog::findOrFail($id);

        if ($request->image != '') {
            $rules['image'] = 'image';
        }

        // here we will update product
        $blogCategory->title = $request->title;
        $blogCategory->description = $request->description;

        $blogCategory->blog_category_id = $request->blog_category_id;
        $blogCategory->status = $request->has('status') ? 1 : 0;

        // Save the blog category
        $blogCategory->save();
        if ($request->image != '') {

            // delete old image
            File::delete(public_path('uploads/'.$blogCategory->image));

            // here we will store image
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext; // Unique image name

            // Save image to products directory
            $image->move(public_path('uploads'), $imageName);

            // Save image name in database
            $blogCategory->image = $imageName;
            $blogCategory->save();
        }

        return redirect()->route('blog.index')->with('success', 'blog post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $blogCategory = blog::findOrFail($id);
        //deleting the image from file
        File::delete(public_path('uploads/'.$blogCategory->image));

        $blogCategory->delete();

        return redirect()->route('blog.index')->with('success', 'blog post deleted successfully.');

    }

    public function upload(Request $request)
    {
        if ($request->file('image')) {
            $path = $request->file('image')->store('tmp', 'public');

            return response()->json(['path' => $path]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

    public function revert(Request $request)
    {
        $path = $request->getContent();
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        return response()->json(['success' => true]);
    }
}
