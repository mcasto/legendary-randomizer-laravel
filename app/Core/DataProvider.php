<?php
namespace App\Core;

class DataProvider implements DataProviderInterface
{

 private $db;

 public function __construct($dbConnection)
 {
  $this->db = $dbConnection;
 }

 public function getClassName(int $colorId): string
 {
  $classRow = $this->db->fetch("SELECT * FROM store WHERE list='classes' AND _id=?", $colorId);
  $rec      = json_decode($classRow->rec);

  return $rec->label;
 }

 public function getClassIcon(int $colorId): string
 {
  return "/images/hero-classes/$colorId.svg";
 }

 public function getRarityName(int $rarityId): string
 {
  $classRow = $this->db->fetch("SELECT * FROM store WHERE list='rarities' AND _id=?", $rarityId);

  $rec = json_decode($classRow->rec);

  return $rec->label;
 }

 public function getTeamName(int $teamId): string
 {
  $teamRow = $this->db->fetch("SELECT * FROM store WHERE list='teams' AND _id=?", $teamId);
  $rec     = json_decode($teamRow->rec);

  return $rec->label;
 }

 public function getKeyword(int $keywordId): string
 {
  $keywordRow = $this->db->fetch("SELECT * FROM store WHERE list='keywords' AND _id=?", $keywordId);
  $rec        = json_decode($keywordRow->rec);

  return $rec->label;

 }

 public function getAlwaysLeads(int $mastermindId): string
 {
  $mastermindRow = $this->db->fetch("SELECT * FROM store WHERE list='masterminds' AND _id=?", $mastermindId);

  $rec  = json_decode($mastermindRow->rec, true);
  $data = $rec['cards'][0];

  if (! isset($data['abilities']) || ! is_array($data['abilities'])) {
   return '!!!!';
  }

  foreach ($data['abilities'] as $ability) {
   if (is_array($ability) && isset($ability[0]['bold']) && $ability[0]['bold'] === "Always Leads") {

    return trim(str_replace(':', '', $ability[1])); // Join remaining elements as string
   }
  }

  return '!!!!'; // Return '!!!!' if "Always Leads" not found to indicate a problem since there should always be an Always Leads for a mastermind

 }
}
