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
        // noop: migration moved to a later timestamp to ensure works table is
        // created before translations. Left as no-op to preserve migration history.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // noop
    }
};
