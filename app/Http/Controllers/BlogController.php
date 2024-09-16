<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Models\blog;
use App\Models\blog_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\storage;
use Intervention\Image\Facades\Image;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = blog::orderBy('id', 'asc')->paginate(10); // Adjust the number per page as needed

        // Return the view with the paginated data
        return view('admin.blog.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $blog = blog_category::all();

        return view('admin.blog.create', compact('blog'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogRequest $request)
    {
        //
        // dd($request->image);
        $blog = new blog;
        $blog->title = $request->title;
        $blog->published_at = $request->published_at;
        $blog->description = $request->description;

        $blog->blog_category_id = $request->blog_category_id;
        $blog->status = $request->has('status') ? 1 : 0;

        // Save the blog category
        // If an image is uploaded, save it to the uploads folder and update the blog category

        if ($request->input('image')) {
            $imagePath = $request->input('image');
            $filename = basename($imagePath);

            //path for the original image and resized image
            $originalPath = 'images/'.$filename;
            $resizedPath = 'images/resized/'.$filename;

            //move the file from temporary to original place
            Storage::disk('public')->move($imagePath, $originalPath);

            //resize the image
            $resizedImage = Image::make(storage_path('app/public/'.$originalPath))->resize(300, 300);

            //store the image in the resized folder
            Storage::disk('public')->put($resizedPath, (string) $resizedImage->encode());

            //saved in the database
            $blog->image = $originalPath;

        }
        $blog->save();

        // Redirect to the blog category index with a success message eith id also
        return redirect()->route('blog.show', $blog->id)->with('success', 'Blog post added successfully.');

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
        $blog = blog::findOrFail($id);
        // Fetch all categories for the parent category select dropdown
        $categories = blog_category::all();

        return view('admin.blog.edit', compact('blog', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRequest $request, string $id)
    {
        $blog = blog::findOrFail($id);

        // here we will update product
        $blog->title = $request->title;
        $blog->description = $request->description;

        $blog->blog_category_id = $request->blog_category_id;
        $blog->status = $request->has('status') ? 1 : 0;

        // Save the blog category
        if ($request->input('image')) {
            // Delete old images if they exist
            if ($blog->image) {
                File::delete(public_path('storage/'.$blog->image));
                File::delete(public_path('storage/images/resized/'.basename($blog->image)));
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
            $blog->image = $originalPath;
        }
        $blog->save();

        return redirect()->route('blog.index')->with('success', 'blog post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $blog = blog::findOrFail($id);
        //deleting the image from file
        File::delete(public_path('uploads/'.$blog->image));

        $blog->delete();

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

    public function updateStatus(Request $request)
    {

        $blog = Blog::find($request->id);

        if ($blog) {
            $blog->status = $request->status;

            $blog->save();

            return response()->json(['success' => true, 'status' => $blog->status]);
        }

        return response()->json(['success' => false]);
    }
}
