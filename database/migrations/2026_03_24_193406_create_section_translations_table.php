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
		Schema::create('section_translations', function (Blueprint $table) {
			$table->id();
			$table->foreignId('section_id')->constrained()->cascadeOnDelete();
			$table->foreignId('language_id')->constrained()->cascadeOnDelete();
			$table->string('title')->nullable();
			$table->string('subtitle')->nullable();
			$table->text('body')->nullable();
			$table->string('cta_primary_label')->nullable();
			$table->string('cta_primary_url')->nullable();
			$table->string('cta_secondary_label')->nullable();
			$table->string('cta_secondary_url')->nullable();
			$table->json('items')->nullable();
			$table->timestamps();

			$table->unique(['section_id', 'language_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('section_translations');
	}
};
