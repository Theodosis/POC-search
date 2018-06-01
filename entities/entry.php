<?php

namespace HelloSign\entities;

class Entry {
  public $type;
  public $id;
  public $weight;
  public $text;

  public function __construct($type, $id, $weight, $text) {
    $this->type = $type;
    $this->id = $id;
    $this->weight = $weight;
    $this->text = $text;
  }

  public function calculateWeight(float $byType = 1.0, float $byId = 1.0) {
    return $this->weight * $byType * $byId;
  }
}

