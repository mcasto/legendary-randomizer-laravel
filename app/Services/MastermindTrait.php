<?php
namespace App\Services;

trait MastermindTrait
{
 public function alwaysLeads(array &$game, string $fromList, string $toList, string $field, string $value)
 {
  SwapRandomItem::swap($game, $fromList, $toList, $field, $value);
 }
}
