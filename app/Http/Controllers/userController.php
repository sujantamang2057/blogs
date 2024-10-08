<?php

namespace App\Http\Controllers;

use App\DataTables\userDataTable;
use App\Http\Requests\passswordRequest;
use App\Http\Requests\userRequest;
use App\Models\User;
use Carbon\Carbon;
use DB;
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

        $existimage = '800px_'.basename($user->image);
        $currentimage = basename($request->image);
        // dd($existimage, $currentimage);

        if ($existimage != $currentimage) {

            // Delete old images if they exist
            if ($request->input('image')) {
                if ($user->image) {
                    if (Storage::exists(public_path('storage/'.$user->image))) {
                        Storage::delete(public_path('storage/'.$user->image));
                    }
                    if (Storage::exists(public_path('storage/images/resized/800px_'.basename($user->image)))) {
                        Storage::delete(public_path('storage/images/resized/800px_'.basename($user->image)));
                    }
                    if (Storage::exists(public_path('storage/images/resized/100px_'.basename($user->image)))) {
                        Storage::delete(public_path('storage/images/resized/100px_'.basename($user->image)));
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
                $user->image = $originalPath;
            } else {

                $user->image = $user->image;
            }
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

    public function password(string $id)
    {
        $user = user::find($id);

        return view('admin.user.password', compact('user'));
    }

    public function updatePassword(passswordRequest $request, string $id)
    {

        $user = user::findorfail($id);
        if ($request->current_password && $request->new_password) {
            if (Hash::check($request->current_password, $user->password)) {
                if (Hash::check($request->new_password, $user->password)) {

                    return redirect()->route('password', $user->id)->with('error', 'The new password is same as old password');

                }

                $user->password = Hash::make($request->new_password);
                $user->save();

                return redirect()->route('user.show', $user->id)->with('success', 'password updated successfully');
            } else {

                return redirect()->route('password', $user->id)->with('error', 'current password not match');
            }

        } elseif ($request->confirm_password && $request->new_password) {
            if ($request->new_password == $request->confirm_password) {
                $user->password = Hash::make($request->new_password);

                $user->save();

                return redirect()->route('user.show', $user->id)->with('success', 'password updated successfully');
            } else {

                return redirect()->route('password', $user->id)->with('error', 'password not match');
            }

        }

    }

    public function bulkUpdateStatus(Request $request)
    {
        $ids = $request->ids;

        User::whereIn('id', $ids)
            ->where('id', '!=', Auth::id()) // Exclude the current logged-in user from changing status
            ->update(['status' => DB::raw('NOT status')]);

        return response()->json(['success' => 'Status updated successfully!']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        User::whereIn('id', $ids)
            ->where('id', '!=', Auth::id())//Exclude the current logged-in user froim getting deleted
            ->delete();

        return response()->json(['success' => 'Selected rows deleted successfully!']);
    }
}
