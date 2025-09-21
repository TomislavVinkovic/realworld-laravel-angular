<?php

use App\Models\User;
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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            $table->string('slug');
            $table->string('title');
            $table->string('description');
            $table->string('body');

            $table->json('tags')->nullable();
            $table->unsignedBigInteger('count')
                ->min(0);

            $table->foreignIdFor(User::class, 'author_id')
                ->constrained()
                ->onDelete("cascade");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
