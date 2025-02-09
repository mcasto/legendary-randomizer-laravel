<?php
namespace App\Http\Controllers;

use App\Models\DefaultSetups;
use App\Models\User;
use App\Services\HandlerNamingConvention;
use App\Services\WeightedShuffle;
use function Illuminate\Log\log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GameRandomizerController extends Controller
{
 protected $weightedShuffle;
 private $game, $handlerList = [];

 public function __construct(WeightedShuffle $weightedShuffle)
 {
  $this->weightedShuffle = $weightedShuffle;
 }

 public function generateGame(Request $request): ?JsonResponse
 {
  // mc-todo: add ability to specify scheme and/or mastermind (maybe other entities)

  $numPlayers = intval($request->numPlayers);

  // these can be specified in the front end by the user, otherwise they default to false
  $scheme     = $request->scheme;
  $mastermind = $request->mastermind;

  $this->game = [
   'scheme'         => $scheme ?? false,
   'mastermind'     => $mastermind ?? false,
   'masterList'     => $this->getMasterList(),
   'handlerResults' => [],
  ]; // the master list starts as the list of possible items from the user's expansions, it then gets used to determine

  if (! $this->game['masterList']) {
   return null; // user not logged in
  }

  // Get game setup according to default setup for number of players
  $this->game['setup'] = DefaultSetups::getSetup($numPlayers);

  // Define categories in order in which their handlers take precedence: Schemes → Masterminds → Villains → Henchmen → Heroes
  $categories = ['schemes', 'masterminds', 'villains', 'henchmen', 'heroes'];

  // Get category entities according to setup
  foreach ($categories as $category) {
   $this->getEntities($category);
  }

  // mc-todo: while count( $this->handlerList)>0 { $handler=array_shift($this->handlerList); run $handler; -- $handler might cause another handler to be added to $this->handlerList, but that should just get picked up in the while loop }

  $handlerClass     = 'App\Services\Handlers\\' . 'Masterminds_0a161f430b77a54e64bc3519a90afacc_Handler';
  $handlerInterface = new $handlerClass();
  $handlerInterface->handle($this->game);

  while (count($this->handlerList) > 0) {
   $keys    = array_keys($this->handlerList);
   $key     = array_shift($keys);
   $handler = array_shift($this->handlerList);

   // Assuming the handler name includes the full namespace (e.g., "App\Services\Handlers\Schemes_6128a9446e94840dd480621369087ccd_Handler")
   $handlerClass = 'App\Services\Handlers\\' . $handler;

   // Check if the class exists before calling it to prevent errors (it should always exist, but this adds a failsafe for weird edge cases)
   if (class_exists($handlerClass)) {
    // Instantiate the handler class
    $handlerInstance = new $handlerClass();

    // Call a specific method on the handler (e.g., `handle()`)
    if (method_exists($handlerInstance, 'handle')) {
     $this->game['handlerResults'][$key] = $handlerInstance->handle($this->game);
    } else {
     Log::error("Method 'handle' not found in handler: {$handlerClass}");
    }
   } else {
    Log::error("Handler class not found: {$handlerClass}");
   }
  }

  // Return the game setup as a JSON response
  return response()->json(['game' => $this->game, 'handlers' => $this->handlerList]);
 }

 private function getMasterList(): ?array
 {
  $user = User::where('id', Auth::id())->find(1);
  if (! $user) {
   Log::error("You need to log in");
   return null;
  }

  return [
   'schemes'     => $this->weightedShuffle->shuffle('schemes', $user->settings),
   'masterminds' => $this->weightedShuffle->shuffle('masterminds', $user->settings),
   'villains'    => $this->weightedShuffle->shuffle('villains', $user->settings),
   'henchmen'    => $this->weightedShuffle->shuffle('henchmen', $user->settings),
   'heroes'      => $this->weightedShuffle->shuffle('heroes', $user->settings),
  ];
 }

 private function getEntities($type)
 {
  for ($counter = 0; $counter < $this->game['setup'][$type]; $counter++) {
   $entity    = array_shift($this->game['masterList'][$type]);
   $list      = $entity['list'];
   $rec       = $entity['rec'];
   $name      = $rec->name;
   $expansion = $rec->set;

   $handlerName                                 = HandlerNamingConvention::format($list, $name, $expansion);
   $this->handlerList["$list-$name-$expansion"] = $handlerName;
   $this->handlerList                           = array_unique($this->handlerList);

   $this->game['deck'][$type][] = $entity;
  }
 }
}
