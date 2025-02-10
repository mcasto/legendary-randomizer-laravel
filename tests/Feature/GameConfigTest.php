<?php
namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Core\GameConfiguration;
use App\Core\Testing\Seeders\SeedHenchmen;
use App\Core\Testing\Seeders\SeedHeroes;
use App\Core\Testing\Seeders\SeedMasterminds;
use App\Core\Testing\Seeders\SeedSchemes;
use App\Core\Testing\Seeders\SeedVillains;
use App\Core\User;
use Tests\TestCase;

class GameConfigTest extends TestCase
{
 /**
  * A basic test example.
  */
 public function testItCreatesAGameConfiguration(): void
 {
  // Arrange
  //  numPlayers
  $numPlayers = 2;

  // dataSet
  $dataset = [
   'heroes'      => (new SeedHeroes)->seed(),
   'villains'    => (new SeedVillains)->seed(),
   'schemes'     => (new SeedSchemes)->seed(),
   'masterminds' => (new SeedMasterminds)->seed(),
   'henchmen'    => (new SeedHenchmen)->seed(),
  ]; // full list of entities

  $user = new User();
  $user->setUseEpics(false);
  $user->setUseWeightedShuffle(true);
  $user->setExpansions([1]);

  // Act
  $gameConfiguration = new GameConfiguration();
  $gameConfiguration->setNumPlayers($numPlayers);
  $gameConfiguration->setUser($user);
  $gameConfiguration->setData($dataset);

  $gameConfiguration->generateGame();

  // Assert--I changed to "assertsEqual" and returned text for true so I would know which assertion failed

  // mc-note: *** Should this be part of a second test? ****
  $this->assertEquals($user->isValid(), 'expansions-found');
  // *******************************************************

  $this->assertEquals($gameConfiguration->isValid(), 'config-valid');
 }
}
