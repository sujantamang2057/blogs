<?php

namespace App\Http\Controllers;

use App\Models\Blog;

class DashboardController extends Controller
{
    //

    public function index()
    {
        $activecount = Blog::where('status', '1')->count();
        $inactivecount = Blog::where('status', '0')->count();

        return view('admin.dashboard.index', compact('activecount', 'inactivecount'));
    }

    public function newblogs()
    {
        return view('admin.dashboard.blog');

    }
}
