<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultSetups extends Model
{
 protected $table   = 'default_setups';
 public $timestamps = false;

 public static function getSetup(int $numPlayers)
 {
  return DefaultSetups::where('players', $numPlayers)->first()->toArray();
 }
}
