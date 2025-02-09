<?php
namespace App\Core;

class GameConfiguration
{
 private $numPlayers, $user, $dataSet;

 public function __construct($numPlayers, $dataSet, User $user)
 {
  $this->numPlayers = $numPlayers;
  $this->user       = $user;
  $this->dataSet    = $dataSet;
  $this->user       = new User;
 }

 public function isValid()
 {
  if ($this->numPlayers < 1 || $this->numPlayers > 5) {
   return false;
  }

  return 'config-valid';
 }

}
