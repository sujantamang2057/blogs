<?php

// routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;
// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard'));
});

// Home > Blog
Breadcrumbs::for('blog', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Blog', route('blog.index'));
});

Breadcrumbs::for('blog-create', function (BreadcrumbTrail $trail) {
    $trail->parent('blog');

    $trail->push('Create', route('blog.create'));
});

Breadcrumbs::for('blog-show', function (BreadcrumbTrail $trail, $blog) {
    $trail->parent('blog');
    $trail->push('View /'.$blog->title, route('blog.show', $blog));
});

Breadcrumbs::for('blog-update', function (BreadcrumbTrail $trail, $blog) {
    $trail->parent('blog');

    $trail->push('Update/'.$blog->title, route('blog.create', $blog));
});

//for the category of the blog here

Breadcrumbs::for('category', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Category', route('category.index'));
});

Breadcrumbs::for('category-create', function (BreadcrumbTrail $trail) {
    $trail->parent('category');

    $trail->push('Create', route('category.create'));
});

Breadcrumbs::for('category-show', function (BreadcrumbTrail $trail, $blogCategory) {
    $trail->parent('category');
    $trail->push('View /'.$blogCategory->title, route('category.show', $blogCategory));
});

Breadcrumbs::for('category-update', function (BreadcrumbTrail $trail, $blogCategory) {
    $trail->parent('category');

    $trail->push('Update/'.$blogCategory->title, route('category.update', $blogCategory));
});

// for the user bread cumb
Breadcrumbs::for('user', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('User', route('user.index'));
});

Breadcrumbs::for('user-create', function (BreadcrumbTrail $trail) {
    $trail->parent('user');

    $trail->push('Create', route('user.create'));
});

Breadcrumbs::for('user-show', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('user');
    $trail->push('View /'.$user->name, route('user.show', $user));
});

Breadcrumbs::for('user-update', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('user');

    $trail->push('Update/'.$user->name, route('user.update', $user));
});
