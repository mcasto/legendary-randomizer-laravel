<?php
namespace App\Services;

interface HandlerInterface
{
 /**
  * Modify the game setup.
  *
  * @param array $gameSetup The current game setup.
  * @return array|null Returns the modified game setup or null if no changes are made.
  */
 public function handle(array &$game);
}
