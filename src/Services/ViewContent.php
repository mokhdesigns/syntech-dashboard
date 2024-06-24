<?php

namespace Syntech\Dashboard\Services;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class ViewContent
{
    public function getViewContent($namespace)
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
