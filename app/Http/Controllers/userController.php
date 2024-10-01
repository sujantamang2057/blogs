<?php

namespace App\Http\Controllers;

use App\DataTables\userDataTable;
use App\Http\Requests\userRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\storage;
use Intervention\Image\Facades\Image;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(userDataTable $dataTable)
    {

        return $dataTable->render('admin.user.index');
    }
    // public function index()
    // {
    //     //
    //     $user = User::orderBy('id', 'asc')->paginate(10)->all();

    //     return view('admin.user.index', compact('user'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(userRequest $request)
    {
        // Create a new user instance

        $user = new User;

        // Hash the password
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->phone = $request->phone;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = $request->has('status') ? 1 : 0;
        // Set created_at and updated_at with Nepal timezone
        $currentTime = Carbon::now();
        $user->created_at = $currentTime;
        $user->created_by = Auth::user()->name;

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
            $user->image = $originalPath;
        }

        // Set email_verified_at to null if email is changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Save the new user
        $user->save();

        return redirect()->route('user.show', $user->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = user::findorfail($id);

        return view('admin.user.show', compact('user'));
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = user::findorfail($id);

        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(userRequest $request, string $id)
    {

        $user = user::findorfail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = $request->has('status') ? 1 : 0;

        $user->phone = $request->phone;
        $user->updated_by = Auth::user()->name;

        if ($request->input('image')) {
            // Delete old images if they exist
            if ($user->image) {
                if (Storage::exists(public_path('storage/'.$user->image))) {
                    Storage::delete(public_path('storage/'.$user->image));
                }
                if (Storage::exists(public_path('storage/images/resized/'.basename($user->image)))) {
                    Storage::delete(public_path('storage/images/resized/'.basename($user->image)));
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
            $user->image = $originalPath;
        }

        $user->save();

        return redirect()->route('user.show', $user->id);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = user::findorfail($id);
        if ($user->image) {
            Storage::disk('public')->delete('images/original/'.$user->image);
            Storage::disk('public')->delete('images/resized/'.$user->image);
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'user deleted successfully');
    }

    public function updateStatus(Request $request)
    {

        $user = user::find($request->id);

        if ($user) {
            $user->status = $request->status;
            $user->updated_by = Auth::user()->name;

            $user->save();

            return response()->json(['success' => true, 'status' => $user->status]);
        }

        return response()->json(['success' => false]);
    }
}
