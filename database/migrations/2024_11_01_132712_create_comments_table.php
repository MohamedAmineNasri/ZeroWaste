<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('comments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Link to users table
      $table->foreignId('best_practice_id')->constrained()->onDelete('cascade');
      $table->text('content');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('comments');
  }
};