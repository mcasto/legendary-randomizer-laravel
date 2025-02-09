<?php
namespace App\Core\Testing\Seeders;

use App\Core\Scheme;
use Illuminate\Support\Str;

class SeedSchemes
{
 function seed(): array
 {
  $schemes = [];

  for ($counter = 0; $counter < 10; $counter++) {
   $schemes[] = new Scheme(
    $counter + 1,
    "NAME: " . Str::random(),
    "EXPANSION: " . strtolower(Str::random(4)),
   );
  }

  // returns an array of Hero mockups
  return $schemes;
 }
}
