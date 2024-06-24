<?php

namespace Syntech\Dashboard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Syntech\Dashboard\Services\ControllerContent;
use Syntech\Dashboard\Services\DatatableContent;
use Syntech\Dashboard\Services\MigrationContent;
use Syntech\Dashboard\Services\ModelContent;
use Syntech\Dashboard\Services\RequestContent;
use Syntech\Dashboard\Services\ResourceContent;
use Syntech\Dashboard\Services\ViewContent;

use function Laravel\Prompts\confirm;

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
        $modelPath = app_path("Models/{$modelName}.php");
        $migrationPath = database_path('migrations/' . date('Y_m_d_His') . '_create_' . Str::snake($modelName) . '_table.php');

        // Create directories if they don't exist
        File::makeDirectory(dirname($controllerPath), 0755, true, true);
        File::makeDirectory(dirname($datatablePath), 0755, true, true);
        File::makeDirectory(dirname($requestPath), 0755, true, true);
        File::makeDirectory(dirname($resourcePath), 0755, true, true);
        File::makeDirectory(dirname($viewPath), 0755, true, true);

        if(confirm('Do you want to create a model and migration file?')){

            File::makeDirectory(dirname($modelPath), 0755, true, true);
            File::makeDirectory(dirname($migrationPath), 0755, true, true);

            File::put($modelPath, $this->getModelContent($namespace));
            File::put($migrationPath, $this->getMigrationContent($namespace));

        }


        // Create files with content
        File::put($controllerPath, $this->getControllerContent($namespace, $modelName));
        File::put($datatablePath, $this->getDatatableContent($namespace, $modelName, $modelPath));
        File::put($requestPath, $this->getRequestContent($namespace));
        File::put($resourcePath, $this->getResourceContent($namespace));
        File::put($viewPath, $this->getViewContent($namespace));

        $this->info('Dashboard resources created successfully.');
    }

    /**
     * Get the content for the controller file
     *
     * @param string $namespace
     * @param string $modelName
     * @return string
     */

    protected function getControllerContent($namespace, $modelName)
    {
        $content = new ControllerContent();

        return $content->getControllerContent($namespace, $modelName);
    }

    /**
     * Get the content for the datatable file
     *
     * @param string $namespace
     * @return string
     */

    protected function getDatatableContent($namespace, $modelName, $modelPath)
    {
        $content = new DatatableContent();

        return $content->getDatatableContent($namespace, $modelName, $modelPath);
    }

    /**
     * Get the content for the request file
     *
     * @param string $namespace
     * @return string
     */

    protected function getRequestContent($namespace)
    {
        $content = new RequestContent();

        return $content->getRequestContent($namespace);
    }

    /**
     * Get the content for the resource file
     *
     * @param string $namespace
     * @return string
     */

    protected function getResourceContent($namespace)
    {
       $content = new ResourceContent();

        return $content->getResourceContent($namespace);
    }

    /*
        * Get the content for the view file
        *
        * @param string $namespace
        * @return string
        */


    protected function getViewContent($namespace)
    {
         $content = new ViewContent();

        return $content->getViewContent($namespace);
    }

    /*
        * Get the content for the model file
        *
        * @param string $namespace
        * @return string
        */

    protected function getModelContent($namespace)
    {
        $content = new ModelContent();

        return $content->getModelContent($namespace);
    }



    protected function getMigrationContent($namespace)
    {
        $content = new MigrationContent();

        return $content->getMigrationContent($namespace);
    }
}
