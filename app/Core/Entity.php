<?php
namespace App\Core;

class Entity
{
 private int $id           = -1;
 private string $name      = '';
 private string $expansion = '';

 public function __construct(int $id, string $name, string $type, string $expansion)
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
