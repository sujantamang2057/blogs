<?php

namespace App\Http\Controllers;

use App\DataTables\blogDataTable;
use App\Http\Requests\BlogRequest;
use App\Models\blog;
use App\Models\blog_category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(blogDataTable $dataTable)
    {

        return $dataTable->render('admin.blog.index');
    }
    // public function index()
    // {
    //     $blogs = blog::orderBy('id', 'desc')->paginate(10); // Adjust the number per page as needed

    //     // Return the view with the paginated data
    //     return view('admin.blog.index', compact('blogs'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $blog = blog_category::where('status', 1)->get();

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
        $blog->published_at = $request->published_at ? Carbon::parse($request->published_at) : Carbon::now();
        $blog->description = $request->description;
        if ($request->slug) {
            $blog->slug = $request->slug;

        } else {
            $blog->slug = Str::slug($request->title);

        }

        $blog->blog_category_id = $request->blog_category_id;
        $blog->updated_at = null;
        $blog->status = $request->has('status') ? 1 : 0;
        //for the current time of ktm
        $currentTime = Carbon::now();
        $blog->created_at = $currentTime;

        //for created by adding the current login user to it
        if (! $blog->exists) {
            $blog->created_by = Auth::id();
        }
        //for updated by adding the current login user to it

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
        $blog = blog::findOrFail($id);

        // Convert published_at to a Carbon instance if it's not null
        $blog->published_at = $blog->published_at ? Carbon::parse($blog->published_at) : null;

        // Fetch only active categories
        $categories = blog_category::whereNull('deleted_at') // Not soft deleted
            ->where('status', 1) // Active status
            ->get();

        // Check if the post's category is soft-deleted
        $deletedCategory = blog_category::withTrashed()->find($blog->blog_category_id);

        // If the category is soft-deleted, add it to the categories list
        if ($deletedCategory && ($deletedCategory->trashed() || $deletedCategory->status == 0)) {
            $categories->push($deletedCategory);
        }

        return view('admin.blog.edit', compact('blog', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRequest $request, string $id)
    {
        // dd($request->all());
        $blog = blog::findOrFail($id);

        // here we will update product
        $blog->title = $request->title;
        $blog->description = $request->description;
        //for the slug conditon
        if ($request->slug) {
            $blog->slug = $request->slug;
        } else {
            $blog->slug = Str::slug($request->title);
        }

        $blog->blog_category_id = $request->blog_category_id;
        $blog->status = $request->has('status') ? 1 : 0;
        //for the current time of ktm
        $currentTime = Carbon::now();
        $blog->updated_at = $currentTime;

        $blog->updated_by = Auth::id();

        // Save the blog category
        if ($request->input('image')) {
            // Delete old images if they exist
            if ($blog->image) {
                if (Storage::exists(public_path('storage/'.$blog->image))) {
                    Storage::delete(public_path('storage/'.$blog->image));
                }
                if (Storage::exists(public_path('storage/images/resized/'.basename($blog->image)))) {
                    Storage::delete(public_path('storage/images/resized/'.basename($blog->image)));
                }
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

        return redirect()->route('blog.show', $blog->id)->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $blog = blog::findOrFail($id);
        //deleting the image from file
        if ($blog->image) {
            Storage::disk('public')->delete('images/original/'.$blog->image);
            Storage::disk('public')->delete('images/resized/'.$blog->image);
        }
        $blog->delete();

        return redirect()->route('blog.index')->with('success', 'Blog post deleted successfully.');

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
            $blog->updated_by = Auth::id();

            $blog->save();

            return response()->json(['success' => true, 'status' => $blog->status]);
        }

        return response()->json(['success' => false]);
    }
}
