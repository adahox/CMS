<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('additional_field_values', 'post_uuid')) {
            Schema::table('additional_field_values', function (Blueprint $table) {
                $table->dropForeign(['post_uuid']);
                $table->dropUnique(['post_uuid', 'additional_field_uuid']);
            });

            Schema::table('additional_field_values', function (Blueprint $table) {
                $table->renameColumn('post_uuid', 'target');
            });

            Schema::table('additional_field_values', function (Blueprint $table) {
                $table->unique(['target', 'additional_field_uuid']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('additional_field_values', 'target') && ! Schema::hasColumn('additional_field_values', 'post_uuid')) {
            Schema::table('additional_field_values', function (Blueprint $table) {
                $table->dropUnique(['target', 'additional_field_uuid']);
            });

            Schema::table('additional_field_values', function (Blueprint $table) {
                $table->renameColumn('target', 'post_uuid');
            });

            Schema::table('additional_field_values', function (Blueprint $table) {
                $table->unique(['post_uuid', 'additional_field_uuid']);
                $table->foreign('post_uuid')
                    ->references('uuid')
                    ->on('posts')
                    ->cascadeOnDelete();
            });
        }
    }
};
