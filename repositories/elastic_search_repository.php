<?php

namespace HelloSign\repositories;

use Elasticsearch\Client;
use HelloSign\Commands\AddCommand;
use HelloSign\Commands\DelCommand;
use HelloSign\Commands\QueryCommand;
use HelloSign\Commands\WQueryCommand;

class ElasticSearchRepository implements Repository {

  private $client;

  public function __construct(Client $client) {
    $this->client = $client;
  }

  public function add(AddCommand $command) {
    $this->client->index([
      'index' => 'hellosign',
      'type' => $command->type,
      'id' => $command->id,
      'weight' => $command->weight,
      'body' => [
        'weight' => $command->weight,
        'text' => $command->text
      ]
    ]);
  }

  public function query(QueryCommand $command) {
    $this->client->search([
      'query' => [
        'match' => [
          'text' => [
            'query' => $command->text,
            'operator' => 'and'
          ]
        ]
      ],
      'size' => $command->results,
      'sort' => [
        'weight' => ['order', 'desc']
      ]
    ]);
  }

  public function weightedQuery(WQueryCommand $command) {
    $this->client->search([
      'query' => [
        'match' => [
          'text' => [
            'query' => $command->text,
            'operator' => 'and'
          ]
        ]
      ],
      'size' => $command->results,
      'sort' => [
        '_script' => [
          'type' => 'number',
          'script' => [
            'lang' => 'painless',
            'source' => '(params[doc.type] ?: 1.0) * (params[doc.id]) * doc.weight',
            'params' => $command->boosts
          ]
        ]
      ]
    ]);
  }

  public function delete(DelCommand $command) {
    // TODO: Implement weightedQuery() method.
  }

}
