<?php

namespace HelloSign\repositories;

use HelloSign\Commands\AddCommand;
use HelloSign\Commands\DelCommand;
use HelloSign\Commands\QueryCommand;
use HelloSign\Commands\WQueryCommand;
use HelloSign\entities\Entry;

class MemoryRepository implements Repository {

  public $db = [];

  public function add(AddCommand $command) {
    $this->db[$command->id] = new Entry($command->type, $command->id, $command->weight, $command->text);
  }

  private function queryAll(string $text) : array {
    $query = explode(' ', $text);
    return array_filter(
      $this->db,
      function(Entry $entry) use ($query) {
        return array_reduce(
          $query,
          function($match, $word) use ($entry) {
            return $match && stripos($entry->text, $word) !== false;
          },
          true
        );
      }
    );
  }


  public function query(QueryCommand $command) {
    $results = $this->queryAll($command->text);
    usort(
      $results,
      function(Entry $a, Entry $b) {
        return $b->weight <=> $a->weight;
      }
    );
    return array_slice(
      $results,
      0,
      $command->results
    );
  }

  public function weightedQuery(WQueryCommand $command) {
    $results = $this->queryAll($command->text);
    usort(
      $results,
      function(Entry $a, Entry $b) use ($command) {
        return (
          $b->calculateWeight($command->boosts[$b->id] ?? 1.0, $command->boosts[$b->type] ?? 1.0)
          <=>
          $a->calculateWeight($command->boosts[$a->id] ?? 1.0, $command->boosts[$a->type] ?? 1.0)
        );
      }
    );
    return array_slice(
      $results,
      0,
      $command->results
    );
  }

  public function delete(DelCommand $command) {
    unset($this->db[$command->id]);
  }

}
