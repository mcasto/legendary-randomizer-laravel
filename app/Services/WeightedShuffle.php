<?php
namespace App\Services;

use App\Models\Store;
use ksk88\WeightedLotteryPhp\Lot;

class WeightedShuffle
{
 public function shuffle($list, $settings)
 {
  $expansions     = $settings->expansions;
  $usePlayedCount = $settings->usePlayedCount;

  $store = new Store();
  $recs  = $store->getRecs($list, $expansions);
  $recs  = array_map(function ($row) {
   $row['rec'] = json_decode($row['rec']);
   return $row;
  }, $recs);

  // Check if all played_count values are the same
  $firstCount = $recs[0]['played_count'];
  $allSame    = array_reduce($recs, function ($carry, $rec) use ($firstCount) {
   return $carry && ($rec['played_count'] === $firstCount);
  }, true);

  if (! $usePlayedCount) {
   $allSame = true;
  }

  if ($allSame) {
   shuffle($recs); // Apply uniform random shuffle
  } else {
   usort($recs, function ($a, $b) {
    return $b['played_count'] - $a['played_count']; // Descending order
   });
  }

  $setting = [
   'weight_gradient'     => 1,
   'use_order_as_weight' => true,
  ];

  $lot = new Lot();

  return $lot->pickFromWeightedLottery($recs, count($recs), $setting);
 }
}
