<?php

namespace App\Http\Controllers;

use App\DataTables\AlbumImageDataTable;
use App\Models\Album;
use App\Models\Albumimages;
use Illuminate\Http\Request;

class AlbumimagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Albumimages $albumimages)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Albumimages $albumimages)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Albumimages $albumimages)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Albumimages $albumimages)
    {
        //
    }

    public function albumImage(AlbumImageDataTable $dataTable, $id)
    {
        $album = Album::findOrFail($id);

        return $dataTable->render('admin.albumimage.index', [

            'album' => $album,

        ]);
    }

    public function coverImage($id)
    {

        $albumImage = Albumimages::findOrFail($id);
        $albumid = $albumImage->album_id;
        $album = Album::findOrFail($albumid);
        $album->image = $albumImage->cover_image;

        $album->save();

        return redirect()->route('Album.index');

    }
    
}
