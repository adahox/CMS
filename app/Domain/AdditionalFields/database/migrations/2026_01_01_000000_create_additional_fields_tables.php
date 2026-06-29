<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('additional_fields', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('label');
            $table->string('type');
            $table->json('options')->nullable();
            $table->timestamps();
        });

        Schema::create('additional_field_rules', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('target');
            $table->uuid('additional_field_uuid');
            $table->timestamps();

            $table->unique(['target', 'additional_field_uuid']);
            $table->foreign('additional_field_uuid')
                ->references('uuid')
                ->on('additional_fields')
                ->cascadeOnDelete();
        });

        Schema::create('additional_field_values', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('target');
            $table->uuid('additional_field_uuid');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['target', 'additional_field_uuid']);
            $table->foreign('additional_field_uuid')
                ->references('uuid')
                ->on('additional_fields')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('additional_field_values');
        Schema::dropIfExists('additional_field_rules');
        Schema::dropIfExists('additional_fields');
    }
};
