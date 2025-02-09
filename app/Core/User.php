<?php
namespace App\Core;

class User
{
 private $useEpics, $useWeightedShuffle, $expansions = [];

 public function setUseEpics(bool $value): void
 {
  $this->useEpics = $value;
 }

 public function setUseWeightedShuffle(bool $value): void
 {
  $this->useWeightedShuffle = $value;
 }

 public function setExpansions(array $expansions): void
 {
  $this->expansions = $expansions;
 }

 public function isValid()
 {
  return count($this->expansions) > 0 ? 'user-valid' : false;
 }
}
