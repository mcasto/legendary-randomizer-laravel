<?php
namespace App\Core\Testing\Seeders;

use App\Core\Hero;
use Illuminate\Support\Str;

class SeedHeroes
{
 function seed(): array
 {
  $heroes = [];

  for ($counter = 0; $counter < 10; $counter++) {
   $heroes[] = new Hero(
    $counter + 1,
    "HERO: " . Str::random(),
    "EXPANSION: " . strtolower(Str::random(4)),
   );
  }

  // returns an array of Hero mockups
  return $heroes;
 }
}
