<?php

namespace Syntech\Dashboard\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;



class ControllerContent
{
    public function getControllerContent($namespace, $modelName)
    {
        $controllerName = class_basename($namespace);

        $viewPath = 'admin.' . strtolower($controllerName);

        return <<<EOT
<?php

namespace App\Http\Controllers\\{$namespace};

use Syntech\Dashboard\Http\Controllers\BaseController;
use Syntech\Dashboard\Repositories\BaseRepository;
use App\DataTables\\{$namespace}\\{$controllerName}DataTable;
use App\Http\Requests\\{$namespace}\\{$controllerName}Request;
use App\Http\Resources\\{$namespace}\\{$controllerName}Resource;
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
        \$this->route = 'dashboard.' . strtolower('$controllerName');
        \$this->dataTable = \$dataTable;
        \$this->repository->setRules(\$this->request->isMethod('POST') || \$this->request->isMethod('PUT') ? (new {$controllerName}Request())->rules() : []);
    }


}
EOT;

    }

}
