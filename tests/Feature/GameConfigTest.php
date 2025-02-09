<?php
namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Core\GameConfiguration;
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
  $dataSet = ['heroes' => ['a', 'b', 'c'], 'villains' => [1, 2, 3], 'schemes' => ['s1', 's2'], 'masterminds' => ['m1', 'm2', 'm3'], 'henchmen' => ['h1', 'h2'], 'defaultSetups' => [2 => ['schemes' => 1, 'masterminds' => 1, 'villains' => 2, 'henchmen' => 1, 'heroes' => 5]]];

  // user -- authenticated user provides expansions owned, use epic masterminds, use weighted shuffle
  // $user = ['settings' => ['expansions' => [], 'useEpics' => true, 'useWeighted' => true]];

  $user = new User();
  $user->setUseEpics(false);
  $user->setUseWeightedShuffle(true);
  $user->setExpansions([1]);

  // Act
  $gameConfiguration = new GameConfiguration($numPlayers, $dataSet, $user);

  // Assert
  $this->assertEquals($user->isValid(), 'user-valid');
  $this->assertEquals($gameConfiguration->isValid(), 'config-valid');
 }
}
