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
		Schema::create('work_translations', function (Blueprint $table) {
			$table->id();
			$table->foreignId('work_id')->constrained()->cascadeOnDelete();
			$table->foreignId('language_id')->constrained()->cascadeOnDelete();
			$table->string('title');
			$table->string('short_description')->nullable();
			$table->longText('description')->nullable();
			$table->json('highlights')->nullable();
			$table->timestamps();

			$table->unique(['work_id', 'language_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('work_translations');
	}
};
