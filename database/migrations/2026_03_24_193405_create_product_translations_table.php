<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // noop: this migration was moved to a different timestamp file to ensure
        // the products table is created before the translations. Kept as a no-op
        // to preserve historic migration list.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // noop
    }
};
