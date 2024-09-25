<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\blog_category;

class DashboardController extends Controller
{
    //

    public function index()
    {

        // List of models and their respective class names
        $models = [
            'Category' => blog_category::class,
            'Blog' => Blog::class,

        ];

        $statusCounts = [];

        // Loop through each model to get active and inactive counts
        foreach ($models as $modelName => $modelClass) {
            $counts = $modelClass::select('status', $modelClass::raw('count(*) as count'))
                ->whereIn('status', [0, 1])
                ->groupBy('status')
                ->pluck('count', 'status');

            // Save the counts for each model
            $statusCounts[$modelName] = [
                'active' => $counts[1] ?? 0,
                'inactive' => $counts[0] ?? 0,
            ];

        }

        return view('admin.dashboard.index', compact('statusCounts'));
    }

    public function newblogs()
    {
        return view('admin.dashboard.blog');

    }
}
