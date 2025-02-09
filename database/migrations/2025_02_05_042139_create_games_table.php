<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 public function up()
 {
  Schema::create('games', function (Blueprint $table) {
   $table->id();                // Auto-increment primary key
   $table->json('schemes');     // JSON field for schemes
   $table->json('masterminds'); // JSON field for masterminds
   $table->json('villains');    // JSON field for villains
   $table->json('henchmen');    // JSON field for henchmen
   $table->json('heroes');      // JSON field for heroes
   $table->json('setup');       // JSON field for setup parameters
   $table->boolean('archived')->default(false);
   $table->timestamps(); // Created_at and Updated_at timestamps
  });
 }

 public function down()
 {
  Schema::dropIfExists('games');
 }
};
