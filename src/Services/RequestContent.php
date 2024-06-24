<?php

namespace Syntech\Dashboard\Services;


class RequestContent
{

    public function getRequestContent($namespace)
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

            // Add other rules here
        ];
    }
}
EOT;
    }

 }
