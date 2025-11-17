<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void {
    Schema::create("tweets", function (Blueprint $table) {
      $table->id();
      $table->integer("tweeted_by"); // User-ID
      $table->string("content"); // @Temporary: For now only worry about text
      $table->integer("replies")->nullable(); // ID for tweet in the 'replies' table
      $table->timestamps();
    });
  }
};
