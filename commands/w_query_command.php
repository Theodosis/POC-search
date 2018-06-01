<?php
namespace HelloSign\Commands;


class WQueryCommand extends QueryCommand {

  const PARAMETER_NUMBER = 3;

  public $boosts;

  public function __construct(string $results, array $boosts, string $text) {
    $this->boosts = $boosts;
    parent::__construct($results, $text);
  }
}
