<?php
$sql = file_get_contents(__DIR__ . "/legendary_randomizer_2025-02-03.sql");

preg_match_all("/CREATE TABLE `([^`]+)`/", $sql, $matches);
$tableNames = $matches[1];
$tableNames = array_map(function ($name) {
 if (! stristr($name, "_")) {
  return ucfirst($name);
 }

 $names = explode("_", $name);
 $names = array_map(function ($name) {
  return ucfirst($name);
 }, $names);

 return implode("", $names);
}, $tableNames);

foreach ($tableNames as $name) {
 exec("php artisan make:model $name -a");
}
