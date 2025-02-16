<?php
namespace App\Core;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class Entity
{
 public int $masterStrikeId;
 public string $name;
 public array $cards;
 public string $set;
 public array $keywords;

 public function getKeywords($dataProvider): array
 {
  $keywords = [];

  $iterator = new RecursiveIteratorIterator(
   new RecursiveArrayIterator($this->cards),
   RecursiveIteratorIterator::SELF_FIRST
  );

  foreach ($iterator as $key => $value) {
   if ($key === 'keyword') {
    $keywords[] = $dataProvider->getKeyword($value);
   }
  }

  return array_unique($keywords);
 }

 public function validEntity(): array
 {
  $errors = [];

  $name = $this->name ?? '';
  $set  = $this->set ?? '';

  if (! $this->masterStrikeId) {
   $errors[] = 'Invalid master strike id';
  }

  if ($name == '') {
   $errors[] = 'Invalid name';
  }

  if (! $this->cards || count($this->cards) == 0) {
   $errors[] = 'No cards';
  }

  if ($set == '') {
   $errors[] = 'Invalid set';
  }

  return $errors;
 }
}
