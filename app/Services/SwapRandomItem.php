<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;

class SwapRandomItem
{
 /**
  * Swap an entity from one list to another in the game setup.
  *
  * @param array  $game    Game state array
  * @param string $fromList Source list (e.g., 'heroes')
  * @param string $toList   Destination list (e.g., 'villains')
  * @param string $field    Field to match (e.g., 'name')
  * @param string $value    Value to match (can be string or regex)
  *
  * @return array Updated game state
  */
 public static function swap(array &$game, string $fromList, string $toList, string $field, string $value)
 {
  // Check if entity is already in the destination list
  foreach ($game['deck'][$toList] as $entity) {
   if (self::matchValue($entity[$field] ?? '', $value)) {
    return $game; // Entity already in the correct list, no changes needed
   }
  }

  // Search for the entity in masterList[fromList]
  $foundIndex = null;
  foreach ($game['masterList'][$fromList] as $index => $entity) {
   if (self::matchValue($entity[$field] ?? '', $value)) {
    $foundIndex = $index;
    break;
   }
  }

  // If found in masterList, move it to the deck
  if ($foundIndex !== null) {
   $entity                  = array_splice($game['masterList'][$fromList], $foundIndex, 1)[0];
   $entity['required']      = true; // Mark as required
   $game['deck'][$toList][] = $entity;
   return $game;
  }

  // If not found, search in all other deck lists
  foreach ($game['deck'] as $listName => $deckList) {
   foreach ($deckList as $index => $entity) {
    if (self::matchValue($entity[$field] ?? '', $value)) {
     // Move the entity to the new list
     $movedEntity             = array_splice($game['deck'][$listName], $index, 1)[0];
     $movedEntity['required'] = true; // Mark as required
     $game['deck'][$toList][] = $movedEntity;
     return $game;
    }
   }
  }

  // Entity not found anywhere, log an error
  Log::warning("Entity '{$value}' not found in '{$fromList}' or any deck lists.");
  return $game;
 }

 /**
  * Check if a value matches a pattern (exact or regex).
  *
  * @param string $subject The value to check
  * @param string $pattern The match pattern (string or regex)
  *
  * @return bool True if match, false otherwise
  */
 private static function matchValue(string $subject, string $pattern): bool
 {
  return @preg_match($pattern, '') !== false ? (bool) preg_match($pattern, $subject) : $subject === $pattern;
 }
}
