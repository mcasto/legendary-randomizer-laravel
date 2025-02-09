<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportSqlSeeder extends Seeder
{
 public function run()
 {
  $path = database_path('seeders/sql/dump.sql');

  if (File::exists($path)) {
   DB::unprepared(File::get($path));
   $this->command->info('Database seeded from SQL file!');
  } else {
   $this->command->error('SQL dump file not found!');
  }
 }
}
