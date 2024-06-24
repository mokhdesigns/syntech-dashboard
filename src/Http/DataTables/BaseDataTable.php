<?php

namespace Syntech\Dashboard\Http\DataTables;


use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BaseDataTable extends DataTable
{
    protected $actionDisplay = ['show', 'edit', 'delete'];

    protected $model;

    protected $route;

    public function query()
    {
        return (new $this->model)->newQuery();
    }

    public function html()
    {
        // $buttons  =  ['csv', 'pdf', 'print', 'reset', 'reload', 'copy'];
        $buttons  =  ['csv', 'copy', 'pdf'];

        $buttons[] = [
            //  Button::make('excel'),
            //  Button::make('print'),
            //  Button::make('copy'),
            //  Button::make('reset'),
            //  Button::make('reload'),
        ];

        $file =   'https://cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json';

        return $this->builder()
            ->setTableId('DataTables')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom(
                '<"row text-capitalize"' .
                '<"col-sm-12 d-flex justify-content-center mb-3" B>' .
                '<"col-sm-6 d-flex justify-content-start" f>' .
                '<"col-sm-6 d-flex justify-content-end" l>' .
                '>' .
                'r' .
                't' .
                '<"row align-items-center text-capitalize"' .
                '<"col-sm-6" i>' .
                '<"col-sm-6" p>' .
                '>'
            )
            ->parameters([
                'scrollX' => true,
                'lengthMenu' => [10, 25, 50, 100, 500, 1000],
                'order' => [0, 'desc'],
                'language' => [

                         'url' => $file ,

                ],
                'initComplete' => 'function(settings, json) {
                    // refreshFsLightbox();
                    // KTMenu.createInstances();
                }',
                'drawCallback' => 'function(settings, json) {
                    // refreshFsLightbox();
                    // KTMenu.createInstances();
                }',

                'buttons' => $buttons,
            ])
            // ->buttons($buttons)
        ;
    }

    protected function getColumns()
    {
        return [];
    }

    protected function editColumns($dataTable, $columns)
    {
        foreach ($columns as $column => $callback) {
            $dataTable->editColumn($column, $callback);
        }

        return $dataTable->rawColumns(array_keys($columns));
    }

    protected function filterColumns($dataTable, $columns)
    {
        return $dataTable->filter(function ($query) use ($columns) {
            if (isset(request()->search['active'])) {
                $query->whereActive(request()->search['active']);
            }
            if (isset(request()->search['value'])) {
                $query->where(function ($query) use ($columns) {
                    foreach ($columns as $column) {
                        $query->orWhere($column, 'LIKE', '%' . request()->search['value'] . '%');
                    }
                });
            }
        });
    }

    protected function active($query)
    {
        $button = '';
        if ($query->active == 1) {
            $button = view('dashboard.layouts.packages.datatables.active', ['route' => $this->route, 'id' => $query->id]);
        } else if ($query->active == 0) {
            $button = view('dashboard.layouts.packages.datatables.inactive', ['route' => $this->route, 'id' => $query->id]);
        }
        return $this->route ? $button : '';
    }

    protected function block($query)
    {
        $button = '';
        if ($query->blocked == 0) {
            $button = view('dashboard.layouts.packages.datatables.block', ['route' => $this->route, 'id' => $query->id]);
        } else if ($query->blocked == 1) {
            $button = view('dashboard.layouts.packages.datatables.unblock', ['route' => $this->route, 'id' => $query->id]);
        }
        return $this->route ? $button : '';
    }

    protected function action($query, $extra = '')
    {
        $content = '';
        if (in_array('show', $this->actionDisplay)) {
            $content .= view('inc.packages.datatables.show', ['route' => $this->route, 'id' => $query->id]);
        }
        if (in_array('edit', $this->actionDisplay)) {
            $content .= view('inc.packages.datatables.edit', ['route' => $this->route, 'id' => $query->id]);
        }
        if (in_array('delete', $this->actionDisplay)) {
            $content .= view('inc.packages.datatables.delete', ['route' => $this->route, 'id' => $query->id]);
        }
        return view('inc.packages.datatables.actions', compact('content', 'extra'));
    }

    protected function makeColumn($column, $title, $width, $class = '', $visible = true, $searchable = true, $orderable = true, $exportable = true, $printable = true)
    {
        return Column::make($column)->title($title)->width($width)->addClass($class . ' align-middle')->visible($visible)->searchable($searchable)->orderable($column == 'id' ? true : $orderable)->exportable($exportable)->printable($printable);
    }

    protected function computedColumn($column, $title, $width, $class = '', $visible = true, $searchable = false, $orderable = false, $exportable = false, $printable = false)
    {
        return Column::computed($column)->title($title)->width($width)->addClass($class . ' align-middle')->visible($visible)->searchable($searchable)->orderable($orderable)->exportable($exportable)->printable($printable);
    }

    protected function nameWithImage($name, $image, $url = null)
    {

        return view('inc.packages.datatables.name_with_image', compact('name', 'image', 'url'));
    }

    protected function image($image)
    {

        return $image ? '<img src="' . $image . '" style="width: 50px; height: 50px;">' : 'لا يوجد صوره';
    }


    protected function changeStatus($query)
    {

          $checked = $query->status == 1 ? 'checked' : '';

            return '
                <div class="custom-toggle-switch d-flex align-items-center">
                    <input id="toggleswitchPrimary'.$query->id.'" class="changeStatus" name="status" type="checkbox" '. $checked .' type="checkbox" data-id="'.$query->id . '" data-route="'
                    . route($this->route . '.index') .
                    '" value="1">
                    <label for="toggleswitchPrimary'.$query->id.'" class="label-success"></label>
                </div>
             ';

    }
}
