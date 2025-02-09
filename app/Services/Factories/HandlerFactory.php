<?php
namespace App\Services\Factories;

use App\Services\HandlerInterface;
use App\Services\HandlerNamingConvention;
use function Illuminate\Log\log;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HandlerFactory
{
 /**
  * Create a handler instance based on name and expansion.
  *
  * @param string $name The name of the entity (e.g., "Avengers Vs X-Men").
  * @param string $expansion The expansion set (e.g., "xmen").
  * @return HandlerInterface|null
  */
 public static function create(string $name, string $expansion): ?HandlerInterface
 {
  $handlerName = HandlerNamingConvention::format($name, $expansion);
  $className   = "App\\Services\\Handlers\\" . $handlerName;

  log($handlerName);

  if (class_exists($className)) {
   return new $className();
  }

  Log::info("Handler not found: {$className}");
  return null;
 }
}
