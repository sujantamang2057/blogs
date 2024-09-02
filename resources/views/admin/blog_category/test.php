<?php

namespace App\Http\Controllers;

use App\Models\PostCategory;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostCategory;
use App\Http\Requests\UpdatePostCategory;
use Illuminate\Support\Facades\Storage;

class PostCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Fetch post categories with pagination
        $postCategories = PostCategory::paginate(10); // Adjust the number per page as needed
        
        // Return the view with the paginated data
        return view('admin.post-category.index', compact('postCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch all categories for the parent category select dropdown
        $categories = PostCategory::all();
        return view('admin.post-category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostCategory $request)
    {
        // Create a new post category
        $postcategory = new PostCategory();
        $postcategory->title = $request->title;
        $postcategory->slug = $request->slug;
        $postcategory->parent_id = $request->parent_id; // Add parent_id

        // Handle image upload
        if ($request->hasFile('image')) {
            // Store image in the 'public/images' directory
            $imagePath = $request->file('image')->store('images', 'public');
            $postcategory->image = $imagePath;
        }

        $postcategory->status = $request->has('status') ? 1 : 0;

        $postcategory->save();

        return redirect()->route('post-category.index')
                         ->with('success', 'Post Category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Find the post category by ID
        $postcategory = PostCategory::findOrFail($id);
        // Fetch all categories for the parent category select dropdown
        $categories = PostCategory::all();

        return view('admin.post-category.edit', compact('postcategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostCategory $request, $id)
    {
        // Find the post category by ID
        $postcategory = PostCategory::findOrFail($id);

        $postcategory->title = $request->title;
        $postcategory->slug = $request->slug;
        $postcategory->parent_id = $request->parent_id; // Update parent_id

        // Handle image upload if a new image is provided
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($postcategory->image && Storage::exists('public/' . $postcategory->image)) {
                Storage::delete('public/' . $postcategory->image);
            }
            // Store new image
            $imagePath = $request->file('image')->store('images', 'public');
            $postcategory->image = $imagePath;
        }

            $postcategory->status = $request->has('status') ? 1 : 0;
        $postcategory->save();

        return redirect()->route('post-category.index')
                         ->with('success', 'Post Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the post category by ID
        $postcategory = PostCategory::findOrFail($id);

        // Delete the image file if it exists
        if ($postcategory->image && Storage::exists('public/' . $postcategory->image)) {
            Storage::delete('public/' . $postcategory->image);
        }

        $postcategory->delete();

        return redirect()->route('post-category.index')
                         ->with('success', 'Post Category deleted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Find the post category by ID
        $postcategory = PostCategory::findOrFail($id);

        // Return the view with the post category data
        return view('admin.post-category.show', compact('postcategory'));
    }
}
