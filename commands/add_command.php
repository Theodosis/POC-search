<?php
namespace HelloSign\Commands;


class AddCommand extends Command{

  const PARAMETER_NUMBER = 4;

  public $type;
  public $id;
  public $weight;
  public $text;

  public function __construct(string $type, string $id, string $weight, string $text){
    $this->type = $type;
    $this->id = $id;
    $this->weight = $weight;
    $this->text = $text;
  }
}
