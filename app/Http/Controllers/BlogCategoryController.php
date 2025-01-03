<?php

namespace App\Http\Controllers;

use App\DataTables\blogscatDataTable;
use App\Http\Requests\blogcategoryRequest;
use App\Models\blog_category;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(blogscatDataTable $dataTable)
    {

        return $dataTable->render('admin.blog_category.index');
    }

    public function apiIndex()
    {
        $customers = blog_category::all();

        return response()->json([
            'status' => true,
            'message' => 'Customers retrieved successfully',
            'data' => $customers,
        ], 200);
    }
    // public function index(Request $request)
    // {
    //     //
    //     // Fetch post categories with pagination
    //     $blogCategoriestable = blog_category::orderBy('id', 'desc')->paginate(10); // Adjust the number per page as needed

    //     // Return the view with the paginated data
    //     return view('admin.blog_category.index', compact('blogCategoriestable'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $blogCategories = blog_category::where('status', 1)->get();

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
        //if slug is given by the userv that take that otherwise take a custom made one
        if ($request->slug) {
            $blogCategory->slug = $request->slug;

        } else {
            $blogCategory->slug = Str::slug($request->title);

        }

        $blogCategory->parent_id = $request->parent_id;
        $blogCategory->updated_at = null;
        $blogCategory->status = $request->has('status') ? 1 : 0;
        //to get the current kathamdu time instead of uk time by default
        $currentTime = Carbon::now();
        $blogCategory->created_at = $currentTime;

        if (! $blogCategory->exists) {
            $blogCategory->created_by = Auth::id();
        }

        // Save the blog category

        // If an image is uploaded, save it to the uploads folder and update the blog category
        if ($request->input('image')) {
            $imagePath = $request->input('image');
            $filename = basename($imagePath);

            //path for the original image and resized image
            $originalPath = 'images/'.$filename;
            $resized100Path = 'images/resized/100px_'.$filename;
            $resized800Path = 'images/resized/800px_'.$filename;
            // $resizedPath = 'images/resized/'.$filename;

            //move the file from temporary to original place
            Storage::disk('public')->move($imagePath, $originalPath);

            //resize of 100px
            $resized100Image = Image::make(storage_path('app/public/'.$originalPath))->resize(100, null, function ($constraint) {
                $constraint->aspectRatio(); // Keep aspect ratio
                $constraint->upsize(); // Prevent upsizing
            });

            //store the image in the resized folder
            Storage::disk('public')->put($resized100Path, (string) $resized100Image->encode());

            // for the resized 800
            $resized800Image = Image::make(storage_path('app/public/'.$originalPath))->resize(800, null, function ($constraint) {
                $constraint->aspectRatio(); // Keep aspect ratio
                $constraint->upsize(); // Prevent upsizing
            });
            Storage::disk('public')->put($resized800Path, (string) $resized800Image->encode());

            //saved in the database
            $blogCategory->image = $originalPath;

        }

        $blogCategory->save();

        // Redirect to the blog category index with a success message
        return redirect()->route('category.show', $blogCategory->id)->with('success', 'category added successfully.');

    }

    public function apistore(Request $request)
    {

        try {
            $blogCategory = new blog_category;
            $blogCategory->title = $request->title;
            //if slug is given by the userv that take that otherwise take a custom made one
            if ($request->slug) {
                $blogCategory->slug = $request->slug;

            } else {
                $blogCategory->slug = Str::slug($request->title);

            }
            $blogCategory->save();

            return response()->json([
                'status' => true,
                'message' => 'Customer created successfully',
                'data' => $blogCategory,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error creating customer',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $blogCategory = blog_category::findorfail($id);

        return view('admin.blog_category.show', compact('blogCategory'));

    }

    public function apishow(string $id)
    {
        $blogCategory = blog_category::findorfail($id);

        return response()->json([
            'status' => true,
            'message' => 'Customers shown successfully',
            'data' => $blogCategory,
        ], 200);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //find by id
        $blogCategory = blog_category::findOrFail($id);

        // Fetch all categories for the parent category select dropdown
        $categories = blog_category::whereNull('deleted_at') // Not soft deleted
            ->where('status', 1) // Active status
            ->where('title', '!=', $blogCategory->title)
            ->get();

        // Check if the post's category is soft-deleted
        $deletedCategory = blog_category::withTrashed()->find($blogCategory->parent_id);

        // If the category is soft-deleted, add it to the categories list
        if ($deletedCategory && ($deletedCategory->trashed() || $deletedCategory->status == 0)) {
            $categories->push($deletedCategory);
        }

        return view('admin.blog_category.edit', compact('blogCategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(blogcategoryRequest $request, string $id)
    {
        //

        $blogCategory = blog_category::findOrFail($id);

        // here we will update product
        $blogCategory->title = $request->title;

        $blogCategory->parent_id = $request->parent_id;

        //for the slug conditon
        if ($request->slug) {
            $blogCategory->slug = $request->slug;
        } else {
            $blogCategory->slug = Str::slug($request->title);
        }

        //for the current time of ktm
        $currentTime = Carbon::now();
        $blogCategory->updated_at = $currentTime;
        $blogCategory->updated_by = Auth::id();
        $blogCategory->status = $request->has('status') ? 1 : 0;

        // Save the blog category

        $existimage = '800px_'.basename($blogCategory->image);
        $currentimage = basename($request->image);
        // dd($existimage, $currentimage);

        if ($existimage != $currentimage) {

            // Delete old images if they exist
            if ($request->input('image')) {
                if ($blogCategory->image) {
                    if (Storage::exists(public_path('storage/'.$blogCategory->image))) {
                        Storage::delete(public_path('storage/'.$blogCategory->image));
                    }
                    if (Storage::exists(public_path('storage/images/resized/800px_'.basename($blogCategory->image)))) {
                        Storage::delete(public_path('storage/images/resized/800px_'.basename($blogCategory->image)));
                    }
                    if (Storage::exists(public_path('storage/images/resized/100px_'.basename($blogCategory->image)))) {
                        Storage::delete(public_path('storage/images/resized/100px_'.basename($blogCategory->image)));
                    }
                }

                $imagePath = $request->input('image');
                $filename = basename($imagePath);

                //path for the original image and resized image
                $originalPath = 'images/'.$filename;
                $resized100Path = 'images/resized/100px_'.$filename;
                $resized800Path = 'images/resized/800px_'.$filename;
                // $resizedPath = 'images/resized/'.$filename;

                //move the file from temporary to original place
                Storage::disk('public')->move($imagePath, $originalPath);

                //resize of 100px
                $resized100Image = Image::make(storage_path('app/public/'.$originalPath))->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio(); // Keep aspect ratio
                    $constraint->upsize(); // Prevent upsizing
                });

                //store the image in the resized folder
                Storage::disk('public')->put($resized100Path, (string) $resized100Image->encode());

                // for the resized 800
                $resized800Image = Image::make(storage_path('app/public/'.$originalPath))->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio(); // Keep aspect ratio
                    $constraint->upsize(); // Prevent upsizing
                });
                Storage::disk('public')->put($resized800Path, (string) $resized800Image->encode());

                //saved in the database
                $blogCategory->image = $originalPath;
            } else {

                $blogCategory->image = $blogCategory->image;
            }
        }
        $blogCategory->save();

        return redirect()->route('category.show', $blogCategory->id)->with('success', 'Blog category updated successfully.');
    }

    public function apiupdate(blogcategoryRequest $request, string $id)
    {
        try {

            $blogCategory = blog_category::findOrFail($id);
            $blogCategory->title = $request->title;
            if ($request->slug) {
                $blogCategory->slug = $request->slug;
            } else {
                $blogCategory->slug = Str::slug($request->title);
            }
            $blogCategory->save();

            return response()->json([
                'status' => true,
                'message' => 'Customer updated successfully',
                'data' => $blogCategory,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating customer',
                'error' => $e->getMessage(),
            ], 500);

        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $blogCategory = blog_category::findOrFail($id);
        //deleting the image from file
        if ($blogCategory->image) {
            Storage::disk('public')->delete('images/original/'.$blogCategory->image);
            Storage::disk('public')->delete('images/resized/'.$blogCategory->image);
        }

        $blogCategory->delete();

        return redirect()->route('category.index')->with('success', 'Blog category deleted successfully.');

    }

    public function apidelete(string $id)
    {
        try {
            $blogCategory = blog_category::findOrFail($id);
            $blogCategory->delete();

            return response()->json([
                'status' => true,
                'message' => 'Blog category deleted successfully',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting customer',
                'error' => $e->getMessage(),
            ], 500);

        }

    }

    public function updateStatus(Request $request)
    {

        $blogcategory = blog_category::find($request->id);

        if ($blogcategory) {
            $blogcategory->status = $request->status;
            $blogcategory->updated_by = Auth::id();

            $blogcategory->save();

            return response()->json(['success' => true, 'status' => $blogcategory->status]);
        }

        return response()->json(['success' => false]);
    }

    public function bulkUpdateStatus(Request $request)
    {
        $ids = $request->ids;
        blog_category::whereIn('id', $ids)->update(['status' => DB::raw('NOT status')]);

        return response()->json(['success' => 'Status updated successfully!']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        blog_category::whereIn('id', $ids)->delete();

        return response()->json(['success' => 'Selected rows deleted successfully!']);
    }
}
