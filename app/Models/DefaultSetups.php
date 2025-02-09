<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultSetups extends Model
{
 protected $table   = 'default_setups';
 public $timestamps = false;

 public static function getSetup($numPlayers)
 {
  return DefaultSetups::where('players', intval($numPlayers))->first();
 }
}
