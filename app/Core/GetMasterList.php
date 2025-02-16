<?php
namespace App\Core;

use App\Models\Store;

class GetMasterList
{

 public static function getSchemes($user)
 {
  return Store::all()
   ->where('list', '==', 'schemes')
   ->where('team', 'IN', $user->expansions)
   ->toArray();
 }
}
