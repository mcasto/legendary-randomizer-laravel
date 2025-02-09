<?php
namespace App\Core;

class GameConfiguration
{
 private $numPlayers, $user, $dataset;

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
