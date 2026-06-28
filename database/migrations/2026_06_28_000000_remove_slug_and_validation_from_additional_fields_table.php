<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('additional_fields', function (Blueprint $table) {
            if (Schema::hasColumn('additional_fields', 'slug')) {
                $table->dropColumn('slug');
            }

            if (Schema::hasColumn('additional_fields', 'validation')) {
                $table->dropColumn('validation');
            }
        });
    }

    public function down(): void
    {
        Schema::table('additional_fields', function (Blueprint $table) {
            if (! Schema::hasColumn('additional_fields', 'slug')) {
                $table->string('slug')->unique()->after('uuid');
            }

            if (! Schema::hasColumn('additional_fields', 'validation')) {
                $table->json('validation')->nullable()->after('type');
            }
        });
    }
};
