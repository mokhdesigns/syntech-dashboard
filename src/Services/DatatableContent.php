<?php
namespace Syntech\Dashboard\Services;


class DatatableContent
{
    public function getDatatableContent($namespace, $modelName, $modelPath)
    {
        $datatableName = class_basename($namespace) . 'DataTable';

        return <<<EOT
<?php

namespace App\DataTables\\{$namespace};

use Yajra\DataTables\Services\DataTable;
use Syntech\Dashboard\Http\DataTables\BaseDataTable;
use App\Models\\{$modelName};

class {$datatableName} extends BaseDataTable
{
        protected \$model =  $modelName::class;

    protected \$actionDisplay = ['show', 'edit', 'destroy'];

    protected \$route = 'dashboard.driver';

    public function dataTable(\$query)
    {
        \$dataTable = datatables()->eloquent(\$query);

        \$return = \$this->columns(\$dataTable);

        \$return = \$this->filters(\$dataTable);

        return \$return;
    }

    protected function getColumns()
    {
        return [
            \$this->makeColumn('id', __('#'), '0%'),

            \$this->computedColumn('actions', 'actions', '0%'),
        ];
    }

    protected function columns(\$dataTable)
    {
        return \$this->editColumns(\$dataTable, [

            'status' => function (\$query) {

                return \$this->changeStatus(\$query);
            },

            'actions' => function (\$query) {

                return \$this->action(\$query);

            },
        ]);
    }

    protected function filters(\$dataTable)
    {
        \$column = [];


        return \$this->filterColumns(\$dataTable, \$column);
    }

    protected function filterColumns(\$dataTable, \$columns)
    {
        return \$dataTable->filter(function (\$query) use (\$columns) {

            if (isset(request()->search['active'])) {
                \$query->whereActive(request()->search['active']);
            }

            if (isset(request()->search['value'])) {

                \$query->where(function (\$query) use (\$columns) {

                    foreach (\$columns as \$column) {


                    }
                });
            }
        });
    }

    }
EOT;
    }

}
