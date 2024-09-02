<?php

namespace App\Http\Controllers;

use App\Models\blog_category;
use Illuminate\Http\Request;
use App\Http\Requests\blogcategoryRequest;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
          // Fetch post categories with pagination
          $blogCategories = blog_category::paginate(10); // Adjust the number per page as needed
        
          // Return the view with the paginated data
          return view('admin.blog_category.index', compact('blogCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()

    {
        //
        $blogCategories = blog_category::all();
        return view('admin.blog_category.create',compact('blogCategories'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\blogcategoryRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(blogcategoryRequest $request)
    {
        // Store a new blog category in the database
       
        //
       
         // here we will insert product in db
         $blogCategory = new blog_category();
         $blogCategory->title = $request->title;
         $blogCategory->slug = Str::slug($request->title);
 
         $blogCategory->parent_id = $request->parent_id;
         $blogCategory->status = $request->has('status') ? 1 : 0;

        // Save the blog category
        $blogCategory->save();

        // If an image is uploaded, save it to the uploads folder and update the blog category
        if ($request->image != "") {
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext;

            // Save image to products directory
            $image->move(public_path('uploads'),$imageName);

            // Save image name in database
            $blogCategory->image = $imageName;
            $blogCategory->save();
        }

        // Redirect to the blog category index with a success message
        return redirect()->route('category.index')->with('success','category added successfully.');
       
 

     }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $blogCategory=blog_category::findorfail($id);
        return view('admin.blog_category.show',compact('blogCategory'));
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

        

        if ($request->image != "") {
            $rules['image'] = 'image';
        }


        

        // here we will update product
         $blogCategory->title = $request->title;
         $blogCategory->slug = Str::slug($request->title);
 
         $blogCategory->parent_id = $request->parent_id;
         $blogCategory->status = $request->has('status') ? 1 : 0;

        // Save the blog category
        $blogCategory->save();
        if ($request->image != "") {

            // delete old image
            File::delete(public_path('uploads/'.$blogCategory->image));

            // here we will store image
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext; // Unique image name

            // Save image to products directory
            $image->move(public_path('uploads'),$imageName);

            // Save image name in database
            $blogCategory->image = $imageName;
            $blogCategory->save();
        }        

        return redirect()->route('category.index')->with('success','blog category updated successfully.');
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
        return redirect()->route('category.index')->with('success','blog category deleted successfully.');



    }
}
