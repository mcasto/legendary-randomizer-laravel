<?php
namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
 /**
  * Run the database seeders.
  */

 public function run(): void
 {
  DB::table('users')->insert([
   'name'     => 'Mike & Margaret',
   'email'    => 'castoware@gmail.com',
   'password' => Hash::make(env('SEEDER_ADMIN_PASSWORD') ?: throw new Exception('SEEDER_ADMIN_PASSWORD is not set')),
   'settings' => json_encode(json_decode(file_get_contents(__DIR__ . '/json/user-settings.json'))),
  ]);

  $this->call([ImportSqlSeeder::class]);

  $this->call([DefaultSetups::class]);
 }
}
