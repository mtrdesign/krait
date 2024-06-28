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
        Schema::create('krait_preview_configurations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');

            $table->string('table_name')
                ->nullable();

            // Preview-related props
            $table->string('sort_column')
                ->nullable();
            $table->string('sort_direction')
                ->nullable();
            $table->unsignedSmallInteger('items_per_page')
                ->nullable();
            $table->json('columns_order')
                ->nullable();
            $table->json('columns_width')
                ->nullable();
            $table->json('visible_columns')
                ->nullable();

            // Indexes for fast searching
            $table->index(['uuid']);
            $table->index(['table_name']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('krait_preview_configurations');
    }
};
