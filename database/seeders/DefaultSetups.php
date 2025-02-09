<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultSetups extends Seeder
{
 /**
  * Run the database seeds.
  */
 public function run(): void
 {
  $setups = json_decode(file_get_contents(__DIR__ . '/json/default-setups.json'), true);
  DB::table('default_setups')->insert($setups);
 }
}
