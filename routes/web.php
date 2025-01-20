<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\AlbumimagesController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\userController;
use App\Models\blog_category;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// dash board route

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// authenticate and middleware for profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // blog category controler route
    Route::resource('category', BlogCategoryController::class);

    Route::resource('blog', BlogController::class);
    Route::resource('user', userController::class);
    Route::post('upload', [BlogController::class, 'upload'])->name('upload');
    Route::delete('revert', [BlogController::class, 'revert'])->name('revert');
    Route::post('/blog/new', [BlogController::class, 'updateStatus'])->name('blog.status');
    Route::post('/blog/status/update', [BlogCategoryController::class, 'updateStatus'])->name('blogcategory.status');
    Route::post('/user/status/update', [userController::class, 'updateStatus'])->name('user.status');
    Route::get('/user/password/{id}', [userController::class, 'password'])->name('password');
    Route::put('/user/password/change/{id}', [userController::class, 'updatepassword'])->name('user.password.change');
    Route::post('/blog-category/bulk-update-status', [BlogCategoryController::class, 'bulkUpdateStatus'])->name('blog-category.bulk-update-status');
    Route::post('/blog-category/bulk-delete', [BlogCategoryController::class, 'bulkDelete'])->name('blog-category.bulk-delete');
    Route::post('/blog/bulk-update-status', [BlogController::class, 'bulkUpdateStatus'])->name('blog.bulk-update-status');
    Route::post('/blog/bulk-delete', [BlogController::class, 'bulkDelete'])->name('blog.bulk-delete');
    Route::post('/user/bulk-update-status', [userController::class, 'bulkUpdateStatus'])->name('user.bulk-update-status');
    Route::post('/user/bulk-delete', [userController::class, 'bulkDelete'])->name('user.bulk-delete');
    Route::resource('/Album', AlbumController::class);

    Route::post('/multipleupload', [AlbumController::class, 'multipleUpload'])->name('multipleUpload');
    Route::resource('/Image', AlbumimagesController::class);
    Route::get('/album/{album}/images', [AlbumimagesController::class, 'albumImage'])->name('album.image');
    Route::get('/album/{album}/images/cover', [AlbumimagesController::class, 'coverImage'])->name('image.cover');

    Route::get('/cart/list', [CartController::class, 'Cartlist'])->name('cart.list');

    Route::resource('/cart', CartController::class);
    Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');

});

Route::get('/all-tweets-csv', function () {
    $table = blog_category::all(); // Adjust model name and namespace
    $filename = storage_path('tweets.csv'); // Save file in the storage path

    $handle = fopen($filename, 'w+');

    // Add header row
    fputcsv($handle, ['ID', 'Category Name', 'Status', 'Created At']);

    // Add data rows
    foreach ($table as $row) {
        fputcsv($handle, [$row->id, $row->title, $row->status, $row->created_at]);
    }

    fclose($handle);

    // Return file as a response for download
    return Response::download($filename, 'tweets.csv', [
        'Content-Type' => 'text/csv',
    ])->deleteFileAfterSend(true); // Deletes the file after download
});

Route::get('/all-tweets-pdf', function () {
    $table = blog_category::all(); // Fetch data from the model

    // Prepare HTML content for the PDF
    $html = '<h1>Blog Categories</h1>';
    $html .= '<table border="1" cellspacing="0" cellpadding="5">';
    $html .= '<thead>
                 <tr>
                     <th>ID</th>
                     <th>Category Name</th>
                     <th>Status</th>
                     <th>Created At</th>
                 </tr>
              </thead>';
    $html .= '<tbody>';
    foreach ($table as $row) {
        $html .= '<tr>
                     <td>'.$row->id.'</td>
                     <td>'.$row->title.'</td>
                     <td>'.($row->status ? 'Active' : 'Inactive').'</td>
                     <td>'.$row->created_at.'</td>
                  </tr>';
    }
    $html .= '</tbody></table>';

    // Generate PDF from HTML
    $pdf = Pdf::loadHTML($html);

    // Return the PDF for download
    return $pdf->download('blog_categories.pdf');
});

require __DIR__.'/auth.php';

// For blog post in simple all not using resource controller using simple controler ans route

// for category route using resource controller
