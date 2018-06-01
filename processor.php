<?php

namespace HelloSign;

use HelloSign\Commands\AddCommand;
use HelloSign\Commands\Command;
use HelloSign\Commands\DelCommand;
use HelloSign\Commands\QueryCommand;
use HelloSign\Commands\WQueryCommand;
use HelloSign\repositories\Repository;
use Symfony\Component\Cache\Exception\InvalidArgumentException;

class Processor {

  private $repository;

  public function __construct(Repository $repository) {
    $this->repository = $repository;
  }

  public function process(Command $command) {
    switch (get_class($command)) {
      case AddCommand::class:
        return $this->repository->add($command);
      case QueryCommand::class:
        return $this->repository->query($command);
      case WQueryCommand::class:
        return $this->repository->weightedQuery($command);
      case DelCommand::class:
        return $this->repository->delete($command);
      default:
        throw new InvalidArgumentException;
    }

  }
}
