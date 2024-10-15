<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
  {
    Schema::create('best_practices', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->text('contents');
      $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
      $table->string('tags')->nullable();
      $table->string('image')->nullable(); // Add image column
      $table->timestamps();
    });
  }



  /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('best_practices');
    }
};
