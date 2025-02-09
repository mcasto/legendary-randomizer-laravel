<?php
namespace App\Services;

use Illuminate\Support\Str;

class HandlerNamingConvention
{
 /**
  * Given an entity list and id, format handler name safely for class names and filenames.
  *
  * @param string $list The name of the entity's list (e.g., heroes).
  * @param string $id The entity's id (e.g., 1).
  * @return string A sanitized, PascalCase string safe for filenames and class names.
  */
 public static function format(string $list, string $name, string $expansion): string
 {
  // Convert to PascalCase
  $list = Str::studly($list);

  // md5 hash for name | expansion
  $hash = md5("$name|$expansion");

  return "{$list}_{$hash}_Handler";
 }
}
