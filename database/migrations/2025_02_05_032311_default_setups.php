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

  Schema::create('default_setups', function (Blueprint $table) {
   $table->id();
   $table->integer('players');
   $table->integer('schemes');
   $table->integer('masterminds');
   $table->integer('villains');
   $table->integer('henchmen');
   $table->integer('heroes');
   $table->integer('bystanders');
  });
 }

 /**
  * Reverse the migrations.
  */
 public function down(): void
 {
  Schema::dropIfExists('default_setups');
 }
};
