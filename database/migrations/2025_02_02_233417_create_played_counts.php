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
  Schema::create('played_counts', function (Blueprint $table) {
   $table->id();
   $table->string('list');
   $table->integer('_id');
   $table->integer('user_id');
   $table->integer('count');
   $table->timestamps();
  });
 }

 /**
  * Reverse the migrations.
  */
 public function down(): void
 {
  Schema::dropIfExists('played_counts');
 }
};
