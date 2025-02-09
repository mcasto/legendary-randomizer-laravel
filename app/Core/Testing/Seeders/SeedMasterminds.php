<?php
namespace App\Core\Testing\Seeders;

use App\Core\Mastermind;
use Illuminate\Support\Str;

class SeedMasterminds
{
 function seed(): array
 {
  $schemes = [];

  for ($counter = 0; $counter < 10; $counter++) {
   $masterminds[] = new Mastermind(
    $counter + 1,
    "NAME: " . Str::random(),
    "EXPANSION: " . strtolower(Str::random(4)),
   );
  }

  // returns an array of Hero mockups
  return $masterminds;
 }
}
