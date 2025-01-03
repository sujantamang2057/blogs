<?php

namespace App\DataTables;

use App\Models\Album;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AlbumDataTable extends DataTable
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
                $editBtn = '<a href="'.route('Album.edit', $row->id).'" class="btn btn-primary btn-sm">
                    <i class="fas fa-pencil-alt"></i>
                </a>';

                // View Button
                $viewBtn = '<a href="'.route('Album.show', $row->id).'" class="btn btn-success btn-sm">
                    <i class="fas fa-eye"></i>
                </a>';

                // Delete Button
                $deleteBtn = '<form id="deleteForm-blog-'.$row->id.'"
                        action="'.route('Album.destroy', $row->id).'"
                        method="POST" style="display:inline;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="button" class="btn btn-danger btn-sm"
                            onclick="handleDelete(\'deleteForm-blog-'.$row->id.'\')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>';

                $imageBtn = '<a href="'.route('album.image', $row->id).'" class="btn btn-success btn-sm">
                  <i class="fas fa-images"></i>
                </a>';

                // Combine all buttons
                return $viewBtn.' '.$editBtn.' '.$deleteBtn.''.$imageBtn;
            })
            ->addColumn('image', function ($row) {
                // Display image with a small thumbnail
                if ($row->image) {
                    return '<a href="'.asset('storage/images/resized/800px_'.basename($row->image)).'" 
                            data-fancybox="gallery" 
                            data-caption="'.$row->title.'">
                            <img src="'.asset('storage/images/resized/100px_'.basename($row->image)).'" 
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

            ->rawColumns(['action', 'image', 'status', 'checkbox']) // Mark columns as raw HTML
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Album $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('id', 'desc');
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
            Column::make('checkbox')
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->title('<input type="checkbox" id="select-all">')
                ->width(30),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-left'),
            Column::make('title')
                ->width(150),

            Column::make('image')
                ->width(50),
            Column::make('status')
                ->exportable(false)
                ->printable(false)
                ->width(50),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Album_'.date('YmdHis');
    }
}