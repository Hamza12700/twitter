<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void {
    Schema::create('likes', function (Blueprint $table) {
      $table->id();
      $table->integer('tweet_id');
      $table->integer('user_id');
      $table->timestamps();
    });
  }
};
