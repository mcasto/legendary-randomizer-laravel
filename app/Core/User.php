<?php
namespace App\Core;

class User
{
 private bool $useEpics           = false;
 private bool $useWeightedShuffle = false;
 private array $expansions        = [];

 public function __construct(bool $useEpics = false, bool $useWeightedShuffle = false, array $expansions = [])
 {
  $this->useEpics           = $useEpics;
  $this->useWeightedShuffle = $useWeightedShuffle;
  $this->expansions         = $expansions;
 }

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

 public function isValid(): string | bool
 {
  return count($this->expansions) > 0 ? 'expansions-found' : false;
 }
}
