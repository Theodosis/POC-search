<?php

namespace HelloSign;

use HelloSign\entities\Entry;
use HelloSign\repositories\MemoryRepository;

require 'repositories/repository.php';
require 'repositories/memory_repository.php';
require 'parser.php';
require 'processor.php';
require 'entities/entry.php';
require 'commands/command.php';
require 'commands/add_command.php';
require 'commands/del_command.php';
require 'commands/query_command.php';
require 'commands/w_query_command.php';

class App {
  private $parser;
  private $processor;
  public function __construct(
    Parser $parser,
    Processor $processor
  ) {
    $this->parser = $parser;
    $this->processor = $processor;
  }

  public function processLine(string $line) : void {
    $command = $this->parser->getCommand($line);
    $result = $this->processor->process($command);
    if (!is_null($result)) {
      $this->display($result);
    }
  }

  public function display($result) {
    echo implode(
      ' ',
      array_map(
        function(Entry $entry) {
          return $entry->id;
        },
        $result
      )
    ) . PHP_EOL;
  }
}

$app = new App(
  new Parser,
  new Processor(
    new MemoryRepository
  )
);

$stdin = fopen('php://stdin', 'r');
$number = intval(fgets($stdin));
for($i = 0; $i < $number; ++$i ){
  $app->processLine(trim(fgets($stdin)));
}
