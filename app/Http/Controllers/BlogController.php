<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Models\latest_blog;
use Illuminate\Support\Facades\File;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class BlogController extends Controller
{
    //
    //This method will show blog page
    public function index() {
        $products = latest_blog::orderBy('created_at','DESC')->get();

        return view('blogs.list',[
            'products' => $products
        ]);
    }

    //This method will show create page


    public function create(){
        return view('blogs.create');
        
    }
    
    //This method will show create page


    public function store(Request $request) {
        $rules = [
           'title' => 'required|string|max:255',
            'description' => 'required|string',
            'name' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',         
        ];

        if ($request->image != "") {
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails()) {
            return redirect()->route('blogs.create')->withInput()->withErrors($validator);
        }

        // here we will insert product in db
        $blogs = new latest_blog();
        $blogs->name = $request->name;
        $blogs->title = $request->title;
        $blogs->description = $request->description;
        $blogs->save();

        if ($request->image != "") {
            // here we will store image
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext; // Unique image name

            // Save image to products directory
            $image->move(public_path('uploads'),$imageName);

            // Save image name in database
            $blogs->image = $imageName;
            $blogs->save();
        }        

        return redirect()->route('blogs.index')->with('success','blog added successfully.');
    }
    //This method will show edit page
    public function edit($id) {
        $product = latest_blog::findOrFail($id);
        return view('blogs.edit',[
            'product' => $product
        ]);
    }

//update the data
    public function update($id, Request $request) {
        

        $blogs = latest_blog::findOrFail($id);

        $rules = [
            'title' => 'required|string|max:255',
             'description' => 'required|string',
             'name' => 'nullable|string',
             'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',         
         ];

        if ($request->image != "") {
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails()) {
            // echo "200";
            return redirect()->route('blogs.edit',$blogs->id)->withInput()->withErrors($validator);
        }

        // here we will update product
        $blogs->name = $request->name;
        $blogs->title = $request->title;
        $blogs->description = $request->description;
        $blogs->save();

        if ($request->image != "") {

            // delete old image
            File::delete(public_path('uploads/'.$blogs->image));

            // here we will store image
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext; // Unique image name

            // Save image to products directory
            $image->move(public_path('uploads'),$imageName);

            // Save image name in database
            $blogs->image = $imageName;
            $blogs->save();
        }        

        return redirect()->route('blogs.index')->with('success','blog updated successfully.');
    }

        //This method will show u[date page

    

     //This method will show delete page

     public function destroy($id) {
        $blogs = latest_blog::findOrFail($id);

       // delete image
       File::delete(public_path('uploads/'.$blogs->image));

       // delete product from database
       $blogs->delete();

       return redirect()->route('blogs.index')->with('success','blog deleted successfully.');
    }

}
