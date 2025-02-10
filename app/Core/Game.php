<?php
namespace App\Core;

class Game
{
 private array $setup;
 private Deck $deck;
 private array $masterList;
 private User $user;

 public function __construct(array $setup, Deck $deck, array $masterList, User $user)
 {
  $this->setup = $setup;
  $this->deck  = $deck;

  // pull initial entities into the deck from $masterList
 }

 public function isValid(): string | bool
 {
  return true;
 }
}
