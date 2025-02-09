<?php
namespace App\Console\Commands;

use App\Models\Store;
use App\Services\HandlerNamingConvention;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeHandler extends Command
{
 protected $signature   = 'make:handler {list} {name} {expansion}';
 protected $description = 'Create a new game setup handler.';

 public function handle()
 {
  $list      = $this->argument('list');
  $name      = $this->argument('name');
  $expansion = $this->argument('expansion');

  $className = HandlerNamingConvention::format($list, $name, $expansion);

  $namespace = "App\\Services\\Handlers";
  $filePath  = app_path("Services/Handlers/{$className}.php");

  if (File::exists($filePath)) {
   $this->error("Handler already exists: {$className}");
   return;
  }

  // Initialize mastermindData
  $mastermindData = null;
  $mastermindRec  = null;
  $alwaysLeads    = null;

  // If the list is 'masterminds', fetch the record from Store
  if ($list === 'masterminds') {
   $mastermindData = Store::findByName($list, $name, $expansion);

   if (! $mastermindData) {
    $this->warn("Warning: No store entry found for mastermind '$name'.");
   } else {
    if (isset($mastermindData['rec'])) {
     $mastermindRec = json_decode($mastermindData['rec'], true);
     $firstCard     = $mastermindRec['cards'][0];

// Loop through the abilities of the first card
     foreach ($firstCard['abilities'] as $ability) {
      // First check if the ability is an array and has the expected structure
      if (is_array($ability)) {
       // Now check if the first element is an array and contains 'bold'
       if (
        isset($ability[0])
        && is_array($ability[0])
        && isset($ability[0]['bold'])
        && $ability[0]['bold'] === 'Always Leads'
       ) {
// Extract the value after ":"
        $alwaysLeads = isset($ability[1]) ? trim(str_replace(':', '', $ability[1])) : ''; // Remove colon if necessary
        echo "Always Leads: " . $alwaysLeads . "\n";                                      // Output Underworld
       }
      } elseif (is_string($ability) && strpos($ability, 'Always Leads') !== false) {
                                                            // If the ability is just a string and contains "Always Leads"
       $alwaysLeads = trim(str_replace(':', '', $ability)); // Handle it as a string and extract the part after ":"
      }
     }

     $this->info("Mastermind: $name");
     $this->info("Always Leads: $alwaysLeads");
    }
   }
  }

  // Generate comment with mastermind data if available
  $mastermindInfo = $alwaysLeads ? "\tAlways Leads: " . $alwaysLeads : "// No Always Leads Found";

  if ($list != 'masterminds') {
   $mastermindInfo = '';
  }

  $mmStub = <<<mmStub
<?php

// List: $list - Name: $name - Expansion: $expansion

/*
$mastermindInfo
*/

namespace $namespace;

use App\Services\HandlerInterface;
use App\Services\SwapRandomItem;

class $className implements HandlerInterface
{
    public function handle(array &\$game)
    {
        //
    }
}
mmStub;

  $stub = <<<PHP
<?php

// List: $list - Name: $name - Expansion: $expansion

/*
$mastermindInfo
*/

namespace $namespace;

use App\Services\HandlerInterface;

class $className implements HandlerInterface
{
    public function handle(array &\$game)
    {
        //
    }
}
PHP;

  File::ensureDirectoryExists(dirname($filePath));
  File::put($filePath, $stub);

  $this->info("Handler created: {$className}");
 }
}
