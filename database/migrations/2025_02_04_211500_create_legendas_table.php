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
        Schema::create('legendas', function (Blueprint $table) {
            $table->id();
            $table->string("hash");
            $table->string("name")->nullable();
            $table->string("folder_id");
            $table->string("hls_id");
            $table->boolean("isForced")->default(false);
            $table->unsignedBigInteger("filme_id");
            $table->timestamps();

            $table->foreign("filme_id")
                ->references("id")
                ->on("filmes");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legendas');
    }
};
