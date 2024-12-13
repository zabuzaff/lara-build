<?php
echo "
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('$data->table_name', function (Blueprint \$table) {
            \$table->id();
";

// Start the foreach loop outside the echo
foreach ($data->columns as $column) {
    if ($column->type != 'foreign') {
        echo "            \$table->" . $column->type . "('" . $column->name . "')" . ($column->is_nullable == 1 ? "->nullable()" : "") . ";\n";
    } else {
        echo "            \$table->" . 'unsignedBigInteger' . "('" . $column->name . "')" . ($column->is_nullable == 1 ? "->nullable()" : "") . ";\n";
    }
}

echo "            \$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('$data->table_name');
    }
};
";
