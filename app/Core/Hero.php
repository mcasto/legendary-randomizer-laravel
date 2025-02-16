<?php
namespace App\Core;

class Hero extends Entity
{
 public array $teams;
 public array $classes;
 public array $classesByRarity;
 private DataProviderInterface $dataProvider;
 private $rec;

 public function __construct($rec, $dataProvider)
 {
  $this->rec = $rec;

  // Initialize the data provider
  $this->dataProvider = $dataProvider;

  $this->masterStrikeId  = $rec->id;
  $this->name            = $rec->name;
  $this->cards           = $rec->cards;
  $this->set             = $rec->set;
  $this->teams           = $this->getTeams($rec->team ?? 0);
  $this->classesByRarity = $this->getClassesByRarity();
  $this->classes         = $this->getClasses();
  $this->keywords        = $this->getKeywords($this->dataProvider);

 }

 private function getClasses(): array
 {
  $ids = array_map(function ($card) {
   return $card->hc ?? 0;
  }, $this->cards);

  $ids = array_unique($ids);

  $classes = array_map(function ($classId) {
   return [
    'name' => $this->dataProvider->getClassName($classId),
    'icon' => $this->dataProvider->getClassIcon($classId),
   ];
  }, $ids);

  usort($classes, function ($a, $b) {
   return $a['name'] > $b['name'] ? 1 : -1;
  });

  return $classes;
 }

 private function getClassesByRarity(): array
 {
  $classes = [];

  $parsed = array_map(function ($card) {
   $className = $this->dataProvider->getClassName($card->hc ?? 0);
   $classIcon = $this->dataProvider->getClassIcon($card->hc ?? 0);
   $rarity    = $this->dataProvider->getRarityName($card->rarity ?? 0);
   return ['name' => $className, 'icon' => $classIcon, 'rarity' => $rarity];
  }, $this->cards);

  foreach ($parsed as $classItem) {
   // if rarity level not already initiated, initiate it as an array
   if (! isset($classes[$classItem['rarity']])) {
    $classes[$classItem['rarity']] = [];
   }

   // place color into rarity level
   $classes[$classItem['rarity']][] = ['name' => $classItem['name'], 'icon' => $classItem['icon']];
  }

  foreach ($classes as $key => $item) {
   usort($classes[$key], function ($a, $b) {
    return $a['name'] > $b['name'] ? 1 : -1;
   });
  }

  return $classes;
 }

 public function getTeams($defaultTeam): array
 {
  $teams = array_map(function ($card) use ($defaultTeam) {
   $team = $card->team ?? $defaultTeam;
   return $this->dataProvider->getTeamName($team);
  }, $this->cards);

  return array_unique($teams);
 }

 public function isValid(): array
 {
  $errors = $this->validEntity();

  if (! $this->teams || count($this->teams) == 0) {
   $errors[] = 'No teams';
  }

  if (! $this->classes || count($this->classes) == 0) {
   $errors[] = 'No classes';
  }

  if (! $this->classesByRarity || count($this->classesByRarity) == 0) {
   $errors[] = 'No classes by rarity';
  }

  if (count($errors) > 0) {
   return ['list' => 'heroes', 'ms_id' => $this->masterStrikeId, 'errors' => $errors];
  }

  return $errors;
 }
}
