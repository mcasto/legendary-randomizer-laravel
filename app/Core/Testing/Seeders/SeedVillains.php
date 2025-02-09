<?php
namespace App\Core\Testing\Seeders;

use App\Core\Villain;
use Illuminate\Support\Str;

class SeedVillains
{
 function seed(): array
 {
  $villains = [];

  for ($counter = 0; $counter < 10; $counter++) {
   $villains[] = new Villain(
    $counter + 1,
    "VILLAIN GROUP: " . Str::random(),
    "EXPANSION: " . strtolower(Str::random(4)),
   );
  }

  // returns an array of Hero mockups
  return $villains;
 }
}
