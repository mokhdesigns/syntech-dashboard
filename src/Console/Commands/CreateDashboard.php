<?php

namespace Syntech\Dashboard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateDashboard extends Command
{
    protected $signature = 'syntech:create {namespace}';
    protected $description = 'Create a custom controller with associated resources';

    public function handle()
    {
        $namespace = $this->argument('namespace');
        $controllerName = class_basename($namespace);
        $baseNamespace = trim(str_replace($controllerName, '', $namespace), '\\');
        $modelName = $controllerName;  // Assuming the model name is the same as the controller name

        // Paths
        $controllerPath = app_path("Http/Controllers/{$baseNamespace}/{$controllerName}Controller.php");
        $datatablePath = app_path("DataTables/{$baseNamespace}/{$controllerName}DataTable.php");
        $requestPath = app_path("Http/Requests/{$baseNamespace}/{$controllerName}Request.php");
        $resourcePath = app_path("Http/Resources/{$baseNamespace}/{$controllerName}Resource.php");
        $viewPath = resource_path("views/{$baseNamespace}/" . Str::snake($controllerName) . ".blade.php");

        // Create directories if they don't exist
        File::makeDirectory(dirname($controllerPath), 0755, true, true);
        File::makeDirectory(dirname($datatablePath), 0755, true, true);
        File::makeDirectory(dirname($requestPath), 0755, true, true);
        File::makeDirectory(dirname($resourcePath), 0755, true, true);
        File::makeDirectory(dirname($viewPath), 0755, true, true);

        // Create files with content
        File::put($controllerPath, $this->getControllerContent($namespace, $modelName));
        File::put($datatablePath, $this->getDatatableContent($namespace));
        File::put($requestPath, $this->getRequestContent($namespace));
        File::put($resourcePath, $this->getResourceContent($namespace));
        File::put($viewPath, $this->getViewContent($namespace));

        $this->info('Dashboard resources created successfully.');
    }

    protected function getControllerContent($namespace, $modelName)
    {
        $controllerName = class_basename($namespace);
        $viewPath = 'admin.location.' . strtolower($controllerName);

        return <<<EOT
<?php

namespace App\Http\Controllers\\{$namespace};

use Syntech\Dashboard\Http\Controllers\BaseController;
use App\DataTables\\{$namespace}\\{$controllerName}DataTable;
use App\Http\Requests\\{$namespace}\\{$controllerName}Request;
use App\Http\Resources\\{$namespace}\\{$controllerName}Resource;
use  Syntech\Dashboard\Repositories\BaseRepository;
use Illuminate\Http\Request;
use App\Models\\{$modelName};

class {$controllerName}Controller extends BaseController
{
    public \$repository;
    public \$request;
    public \$view;
    public \$route;
    public \$dataTable;

    public function __construct(BaseRepository \$repository, Request \$request, {$controllerName}DataTable \$dataTable, {$modelName} \$model)
    {
        \$this->repository = \$repository;
        \$this->request = \$request;
        \$this->repository->setModel(\$model);
        \$this->view = '{$viewPath}';
        \$this->route = 'dashboard.' . strtolower(\$controllerName);
        \$this->dataTable = \$dataTable;
        \$this->repository->setRules(\$this->request->isMethod('POST') || \$this->request->isMethod('PUT') ? (new {$controllerName}Request())->rules() : []);
    }

}
EOT;
    }

    protected function getDatatableContent($namespace)
    {
        $datatableName = class_basename($namespace) . 'DataTable';

        return <<<EOT
<?php

namespace App\DataTables\\{$namespace};

use Yajra\DataTables\Services\DataTable;

class {$datatableName} extends DataTable
{
    public function dataTable(\$query)
    {
        return datatables(\$query);
    }

    public function query()
    {
        // Define query logic
    }

    public function html()
    {
        return \$this->builder()
                    ->columns(\$this->getColumns())
                    ->minifiedAjax()
                    ->addAction(['width' => '80px']);
    }

    protected function getColumns()
    {
        return [
            'id',
            'name',
            // Add other columns here
        ];
    }

    protected function filename()
    {
        return 'Datatable_' . date('YmdHis');
    }
}
EOT;
    }

    protected function getRequestContent($namespace)
    {
        $requestName = class_basename($namespace) . 'Request';

        return <<<EOT
<?php

namespace App\Http\Requests\\{$namespace};

use Illuminate\Foundation\Http\FormRequest;

class {$requestName} extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            // Add other rules here
        ];
    }
}
EOT;
    }

    protected function getResourceContent($namespace)
    {
        $resourceName = class_basename($namespace) . 'Resource';

        return <<<EOT
<?php

namespace App\Http\Resources\\{$namespace};

use Illuminate\Http\Resources\Json\JsonResource;

class {$resourceName} extends JsonResource
{
    public function toArray(\$request)
    {
        return [
            'id' => \$this->id,
            'name' => \$this->name,
            // Add other fields here
        ];
    }
}
EOT;
    }

    protected function getViewContent($namespace)
    {
        $controllerName = class_basename($namespace);
        $snakeName = Str::snake($controllerName);

        return <<<EOT
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{$controllerName} Dashboard</h1>
    <div class="row">
        <div class="col-md-12">
            {!! \$dataTable->table(['class' => 'table table-bordered table-hover']) !!}
        </div>
    </div>
</div>
@endsection

@push('scripts')
{!! \$dataTable->scripts() !!}
@endpush
EOT;
    }
}
