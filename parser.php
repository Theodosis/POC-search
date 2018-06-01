<?php

namespace HelloSign;

use HelloSign\Commands\AddCommand;
use HelloSign\Commands\Command;
use HelloSign\Commands\DelCommand;
use HelloSign\Commands\QueryCommand;
use HelloSign\Commands\WQueryCommand;

class Parser {

  const COMMAND_ADD = 'ADD';
  const COMMAND_QUERY = 'QUERY';
  const COMMAND_WQUERY = 'WQUERY';
  const COMMAND_DEL = 'DEL';

  public function getCommand(string $line) : Command {
    list ($command, $parameters) = explode(' ', $line, 2);
    switch ($command) {
      case self::COMMAND_ADD:
        list($type, $id, $score, $text) = explode(' ', $parameters, AddCommand::PARAMETER_NUMBER);
        return new AddCommand($type, $id, $score, $text);

      case self::COMMAND_QUERY:
        list($results, $text) = explode(' ', $parameters, QueryCommand::PARAMETER_NUMBER);
        return new QueryCommand($results, $text);

      case self::COMMAND_WQUERY:
        // TODO: Extract logic to separated parsers
        list($results, $boostNum, $rest) = explode(' ', $parameters, WQueryCommand::PARAMETER_NUMBER);
        $boostsStrings = explode(' ', $rest, $boostNum + 1);
        $text = array_pop($boostsStrings);
        $boostsArray = array_map(function($boost) {return explode(':', $boost);}, $boostsStrings);
        $boosts = array_combine(
          array_column($boostsArray, 0),
          array_column($boostsArray, 1)
        );
        return new WQueryCommand($results, $boosts, $text);

      case self::COMMAND_DEL:
        list($id) = explode(' ', $parameters, DelCommand::PARAMETER_NUMBER);
        return new DelCommand($id);

      default:
        throw new \Exception(sprintf('Command %s is not supported.', $command));
    }
  }
}
