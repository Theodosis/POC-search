<?php

namespace HelloSign\repositories;

use HelloSign\Commands\AddCommand;
use HelloSign\Commands\DelCommand;
use HelloSign\Commands\QueryCommand;
use HelloSign\Commands\WQueryCommand;

interface Repository {
  public function add(AddCommand $command);
  public function query(QueryCommand $command);
  public function weightedQuery(WQueryCommand $command);
  public function delete(DelCommand $command);
}
