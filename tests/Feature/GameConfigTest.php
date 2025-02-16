<?php
namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Core\GameConfiguration;
use App\Core\GetMasterList;
use App\Core\User;
use App\Models\Store;
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

  $user = new User();
  $user->setUseEpics(false);
  $user->setUseWeightedShuffle(true);
  $user->setExpansions(Store::getSampleExpansions());

  // dataSet
  $masterList = GetMasterList::getSchemes($user);

  // Act
  $gameConfiguration = new GameConfiguration();
  $gameConfiguration->setNumPlayers($numPlayers);
  $gameConfiguration->setUser($user);
  $gameConfiguration->setMasterList($masterList);

  $game = $gameConfiguration->generateGame();

  // Assert--I changed to "assertsEqual" and returned text for true so I would know which assertion failed

  // mc-note: *** Should this be part of a second test? ****
  $this->assertEquals($user->isValid(), 'expansions-found');
  // *******************************************************

  $this->assertEquals($game->isValid(), 'game-valid');
 }
}
