<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Store extends Model
{

 protected $table   = 'store';
 public $timestamps = false;

 public function getRecs($list, $expansions)
 {
  $recs = Store::where('store.list', $list)
   ->leftJoin('played_counts', function ($join) {
    $join->on('store._id', '=', 'played_counts._id')
     ->whereColumn('store.list', 'played_counts.list')
     ->where('played_counts.user_id', Auth::id());
   })
   ->whereIn(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(store.rec, '$.set'))"), $expansions) // JSON filtering
   ->select('store.*', DB::raw('IFNULL(played_counts.count, 1) as played_count'))
   ->get()->toArray();

  return $recs;
 }

 /**
  * Find items in the store table by name and set in the JSON field.
  *
  * @param string $list The list to search in.
  * @param string $name The name to search for in the JSON field.
  * @param string $set The set to search for in the JSON field.
  * @return array The found records.
  */
 public static function findByName($list, $name, $set)
 {
  $items = Store::where('store.list', $list)
   ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(store.rec, '$.name')) = ?", [$name]) // Searching for name in JSON
   ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(store.rec, '$.set')) = ?", [$set])   // Searching for set in JSON
   ->first()
   ->toArray();

  return $items;
 }

}
