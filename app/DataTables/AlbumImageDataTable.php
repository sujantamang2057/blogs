<?php

namespace App\DataTables;

use App\Models\Albumimages;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AlbumImageDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" name="selected_rows[]" value="'.$row->id.'">';
            })
            ->addColumn('action', function ($row) {
                // Edit Button
                $editBtn = '<a href="'.route('Image.edit', $row->id).'" class="btn btn-primary btn-sm">
                    <i class="fas fa-pencil-alt"></i>
                </a>';

                // View Button
                $viewBtn = '<a href="'.route('Image.show', $row->id).'" class="btn btn-success btn-sm">
                    <i class="fas fa-eye"></i>
                </a>';

                // Delete Button
                $deleteBtn = '<form id="deleteForm-blog-'.$row->id.'"
                        action="'.route('Image.destroy', $row->id).'"
                        method="POST" style="display:inline;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="button" class="btn btn-danger btn-sm"
                            onclick="handleDelete(\'deleteForm-blog-'.$row->id.'\')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>';

                $imageBtn = '<a href="'.route('image.cover', $row->id).'" class="btn btn-success btn-sm">
                  <i class="fas fa-images"></i>
                </a>';

                // Combine all buttons
                return $viewBtn.' '.$editBtn.' '.$deleteBtn.''.$imageBtn;
            })
            ->addColumn('cover_image', function ($row) {
                // Display image with a small thumbnail
                if ($row->cover_image) {
                    return '<a href="'.asset('storage/images/resized/800px_'.basename($row->cover_image)).'" 
                            data-fancybox="gallery" 
                            data-caption="'.$row->title.'">
                            <img src="'.asset('storage/images/resized/100px_'.basename($row->cover_image)).'" 
                                 alt="'.$row->title.'" 
                                 style="width: 50px; height: auto;">
                        </a>';
                } else {
                    return '<p>No image available</p>';
                }
            })
            ->addColumn('status', function ($row) {

                return '<label for="status'.$row->id.'" class="form-label"><strong></strong></label>
                <div class="form-check form-switch">
                    <input class="form-check-input" 
                           type="checkbox" role="switch"
                           id="status'.$row->id.'" name="status"
                           data-id="'.$row->id.'" value="1"
                           '.($row->status ? 'checked' : '').'>
                    <label class="form-check-label" for="status'.$row->id.'"></label>
                </div>';
            })

            ->rawColumns(['action', 'cover_image', 'status', 'checkbox']) // Mark columns as raw HTML
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Albumimages $model): QueryBuilder
    {
        $album_id = request()->route('album') | 0;   //the albu, here is a parameter

        return $model::with('galleryalbum')->where('album_id', $album_id)->newQuery(); //the gallery album is a relationship in model and using eager loading
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('album-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('lfrtip')
            ->orderBy(1);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('action')
                ->title('Action')
                ->width(100), // Fixed width for the action column
            Column::make('image_name')
                ->title('Caption')
                ->width(250),

            Column::make('cover_image')
                ->title('Cover Image')
                ->width(100),

            Column::make('status')
                ->title('Status')
                ->width(50),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'AlbumImage_'.date('YmdHis');
    }
}
