<?php
namespace HelloSign\Commands;


class DelCommand extends Command {

  const PARAMETER_NUMBER = 1;

  public $id;

  public function __construct(string $id) {
    $this->id = $id;
  }
}
