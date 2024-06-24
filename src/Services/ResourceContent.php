<?php

namespace Syntech\Dashboard\Services;


class ResourceContent{

    public function getResourceContent($namespace)
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

                        // Add other fields here
                    ];
                }
            }
            EOT;
    }
}
