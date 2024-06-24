<?php

namespace Syntech\Dashboard\Services;
use Illuminate\Support\Str;
class MigrationContent
{
    public function getMigrationContent($namespace)
    {
        $modelName = Str::snake(class_basename($namespace));

        return <<<EOT
        <?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        class Create{$modelName}Table extends Migration
        {
            public function up()
            {
                Schema::create('{$modelName}', function (Blueprint \$table) {
                    \$table->id();

                    // Add other columns here
                    \$table->timestamps();
                });
            }

            public function down()
            {
                Schema::dropIfExists('{$modelName}');
            }
        }
        EOT;
    }

}

