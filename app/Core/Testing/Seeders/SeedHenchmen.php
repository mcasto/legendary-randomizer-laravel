<?php
namespace App\Core\Testing\Seeders;

use App\Core\Henchman;
use Illuminate\Support\Str;

class SeedHenchmen
{
 function seed(): array
 {
  $schemes = [];

  for ($counter = 0; $counter < 10; $counter++) {
   $henchmen[] = new Henchman(
    $counter + 1,
    "NAME: " . Str::random(),
    "EXPANSION: " . strtolower(Str::random(4)),
   );
  }

  // returns an array of Hero mockups
  return $henchmen;
 }
}
