<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = category::orderBy('created_at','DESC')->get();

        return view('blogs.list',[
            'products' => $products
        ]);    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
{
    $category = new category();
    $category->name = $request->name;
    $category->save();

    return redirect()->route('category.index')->with('success','category added successfully.');



    // Proceed with storing data...
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $product = category::findOrFail($id);
        return view('categories.edit',[
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCategoryRequest $request, string $id)
    {
        echo $id;
        //
        $category = category::findOrFail($id);

        $category = new category();
        $category->name = $request->name;
        $category->save();
        return redirect()->route('category.index')->with('success','category updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $category = category::findOrFail($id);
        $category->delete();
        return redirect()->route('category.index')->with('success','category deleted successfully.');




    }
}
