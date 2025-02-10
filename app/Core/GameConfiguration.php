<?php
namespace App\Core;

use App\Models\DefaultSetups;

class GameConfiguration
{
 private $numPlayers, $user, $dataset;

 public function generateGame(): Game
 {
  $setup = DefaultSetups::getSetup($this->numPlayers);

  $deck = new Deck($setup);

  // get default settings for num players
  $game = new Game($setup, $deck);

  return $game;
 }

 public function setNumPlayers($numPlayers): void
 {
  $this->numPlayers = $numPlayers;
 }

 public function setUser(User $user): void
 {
  $this->user = $user;
 }

 public function setData($data): void
 {
  $this->dataset = $data;
 }

 public function isValid()
 {
  if ($this->numPlayers < 1 || $this->numPlayers > 5) {
   return false;
  }

  return 'config-valid';
 }

}
