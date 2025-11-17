<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create("replies", function (Blueprint $table) {
      $table->id();
      $table->integer("tweet_id"); // The original tweet
      $table->integer("reply"); // The replyer's Tweet-ID
      $table->timestamps();
    });
  }
};
