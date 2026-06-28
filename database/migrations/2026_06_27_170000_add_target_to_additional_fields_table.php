<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('additional_field_rules')) {
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
        }

        if (Schema::hasColumn('additional_fields', 'target')) {
            Schema::table('additional_fields', function (Blueprint $table) {
                $table->dropColumn('target');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('additional_field_rules');
    }
};
