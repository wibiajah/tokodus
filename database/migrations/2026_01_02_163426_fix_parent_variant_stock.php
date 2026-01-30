<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Reset stock_pusat parent yang punya children jadi 0
        DB::statement("
            UPDATE product_variants AS parent
            SET stock_pusat = 0
            WHERE parent.parent_id IS NULL
              AND EXISTS (
                SELECT 1 FROM product_variants AS child 
                WHERE child.parent_id = parent.id
              )
        ");
    }

    public function down()
    {
        // No rollback needed - this is data cleanup
    }
};