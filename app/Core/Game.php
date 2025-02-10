<?php
namespace App\Core;

class Game
{
 private array $setup;
 private Deck $deck;

 public function __construct(array $setup, Deck $deck)
 {
  $this->setup = $setup;
  $this->deck  = $deck;
 }
}
