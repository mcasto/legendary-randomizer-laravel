<?php
namespace App\Core;

use App\Models\DefaultSetups;

class GameConfiguration
{
 private $numPlayers, $user, $masterList;

 public function generateGame(): Game
 {
  $setup = DefaultSetups::getSetup($this->numPlayers);
  $deck  = new Deck($setup);

  return new Game($setup, $deck, $this->masterList, $this->user);
 }

 public function setNumPlayers($numPlayers): void
 {
  $this->numPlayers = $numPlayers;
 }

 public function setUser(User $user): void
 {
  $this->user = $user;
 }

 public function setMasterList($masterList): void
 {
  $this->masterList = $masterList;
 }

 public function isValid(): string | bool
 {
  if ($this->numPlayers < 1 || $this->numPlayers > 5) {
   return false;
  }

  return 'config-valid';
 }

}
