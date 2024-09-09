<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\blog_category;

class DashboardController extends Controller
{
    //

    public function index()
    {
        //blog count
        $blogCounts = Blog::whereIn('status', [0, 1])
            ->select('status', Blog::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $activecount = $blogCounts[1] ?? 0; // active blogs count
        $inactivecount = $blogCounts[0] ?? 0; // inactive blogs count

        //category count
        $categoryCounts = blog_category::whereIn('status', [0, 1])
            ->select('status', blog_category::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $activecount1 = $categoryCounts[1] ?? 0; //  category active blogs count
        $inactivecount1 = $categoryCounts[0] ?? 0; //  categoryinactive blogs count

        return view('admin.dashboard.index', compact('activecount', 'inactivecount', 'activecount1', 'inactivecount1'));
    }

    public function newblogs()
    {
        return view('admin.dashboard.blog');

    }
}
