<?php
namespace App\Core;

interface DataProviderInterface
{
 public function getClassName(int $classId): string;
 public function getClassIcon(int $classId): string;
 public function getRarityName(int $rarityId): string;
 public function getTeamName(int $teamId): string;
 public function getKeyword(int $keywordId): string;
 public function getAlwaysLeads(int $mastermindId): string;
}
