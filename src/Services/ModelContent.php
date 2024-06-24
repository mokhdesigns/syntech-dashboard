<?php
namespace Syntech\Dashboard\Services;

class ModelContent
{
    public function getModelContent($namespace)
    {
        $modelName = class_basename($namespace);

        return <<<EOT
            <?php

            namespace App\Models;

            use Illuminate\Database\Eloquent\Factories\HasFactory;
            use Illuminate\Database\Eloquent\Model;

            class {$modelName} extends Model
            {
                use HasFactory;

                protected \$fillable = [

                    // Add other fillable fields here
                ];
            }
            EOT;
    }

}

