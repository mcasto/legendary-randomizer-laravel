<?php
namespace App\Core;

// I originally thought to have a type property here to designate "schemes", "masterminds", etc. Then I realized each entity type will eventually have other properties specific to it, like Hero will have a team property, so I decided to abstract it one layer farther.

class Entity
{
 private int $id           = -1;
 private string $name      = '';
 private string $expansion = '';

 public function __construct(int $id, string $name, string $expansion)
 {
  $this->$id        = $id;
  $this->$name      = $name;
  $this->$expansion = $expansion;
 }

 public function setId(int $id): void
 {
  $this->id = $id;
 }

 public function setName(string $name): void
 {
  $this->name = $name;
 }

 public function setExpansions(string $expansion): void
 {
  $this->expansion = $expansion;
 }

}
