<?php
namespace App\Core;

class Mastermind extends Entity
{
 private DataProviderInterface $dataProvider;
 public string $alwaysLeads;
 private $rec;

 public function __construct($rec, $dataProvider)
 {
  $this->rec = $rec;

  // Initialize the data provider
  $this->dataProvider = $dataProvider;

  $this->masterStrikeId = $rec->id;
  $this->name           = $rec->name;
  $this->cards          = $rec->cards;
  $this->set            = $rec->set;
  $this->keywords       = $this->getKeywords($this->dataProvider);
  $this->alwaysLeads    = $this->dataProvider->getAlwaysLeads($rec->id);
 }

 public function isValid(): array
 {
  $errors      = $this->validEntity();
  $alwaysLeads = $this->alwaysLeads ?? '';

  if ($alwaysLeads == '') {
   $errors[] = 'No Always Leads';
  }

  if (count($errors) > 0) {
   return ['rec' => $this->rec, 'errors' => $errors];
  }
  return $errors;
 }

}
