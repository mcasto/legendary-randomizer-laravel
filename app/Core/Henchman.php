<?php
namespace App\Core;

class Henchman extends Entity
{
 private DataProviderInterface $dataProvider;
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

 }

 public function isValid(): array
 {
  $errors = $this->validEntity();
  if (count($errors) > 0) {
   return ['rec' => $this->rec, 'errors' => $errors];
  }
  return $errors;
 }
}
