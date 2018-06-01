<?php
namespace HelloSign\Commands;


class QueryCommand extends Command {

  const PARAMETER_NUMBER = 2;

  public $results;
  public $text;

  public function __construct(string $results, string $text) {
    $this->results = $results;
    $this->text = $text;
  }

}