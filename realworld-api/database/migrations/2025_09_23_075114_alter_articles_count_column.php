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
        Schema::table('articles', function(Blueprint $table) {
            $table->unsignedBigInteger('count')
                ->default(0)
                ->min(0)
                ->change();
            $table->renameColumn('count', 'favorited_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function(Blueprint $table) {
            $table->unsignedBigInteger('favorited_count')
                ->min(0)
                ->change();
            $table->renameColumn('favorited_count', 'count');

        });
    }
};
