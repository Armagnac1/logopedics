<?php

namespace App\Services\Scout;

use Elastic\Elasticsearch\Client;
use Laravel\Scout\Builder;
use Laravel\Scout\Engines\Engine;

class ElasticsearchEngine extends Engine
{
    protected $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function update($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        $params['body'] = [];

        $models->each(function ($model) use (&$params) {
            $params['body'][] = [
                'index' => [
                    '_index' => $model->searchableAs(),
                    '_id' => $model->getKey(),
                ]
            ];

            $params['body'][] = $model->toSearchableArray();
        });

        $this->elasticsearch->bulk($params);
    }

    public function delete($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        $params['body'] = [];

        $models->each(function ($model) use (&$params) {
            $params['body'][] = [
                'delete' => [
                    '_index' => $model->searchableAs(),
                    '_id' => $model->getKey(),
                ]
            ];
        });

        $this->elasticsearch->bulk($params);
    }

    public function search(Builder $builder)
    {
        $query = [
            'bool' => [
                'must' => [
                    ['query_string' => [
                        'query' => "*{$builder->query}*"
                    ]]
                ]
            ]
        ];

        $params = [
            'index' => $builder->index ?: $builder->model->searchableAs(),
            'body' => [
                'query' => $query,
                'size' => $builder->limit ?? 100
            ]
        ];

        return $this->elasticsearch->search($params);
    }

    public function paginate(Builder $builder, $perPage, $page)
    {
        $query = [
            'bool' => [
                'must' => [
                    ['query_string' => [
                        'query' => "*{$builder->query}*"
                    ]]
                ]
            ]
        ];

        $params = [
            'index' => $builder->index ?: $builder->model->searchableAs(),
            'body' => [
                'query' => $query,
                'from' => ($page - 1) * $perPage,
                'size' => $perPage
            ]
        ];

        return $this->elasticsearch->search($params);
    }

    public function mapIds($results)
    {
        return collect($results['hits']['hits'])->pluck('_id')->values();
    }

    public function map(Builder $builder, $results, $model)
    {
        if (count($results['hits']['hits']) === 0) {
            return collect();
        }

        $keys = collect($results['hits']['hits'])
            ->pluck('_id')
            ->values()
            ->all();

        $models = $model->whereIn(
            $model->getKeyName(),
            $keys
        )->get()->keyBy($model->getKeyName());

        return collect($results['hits']['hits'])->map(function ($hit) use ($models) {
            return $models[$hit['_id']] ?? null;
        })->filter()->values();
    }

    public function lazyMap(Builder $builder, $results, $model)
    {
        if (count($results['hits']['hits']) === 0) {
            return collect();
        }

        $keys = collect($results['hits']['hits'])
            ->pluck('_id')
            ->values()
            ->all();

        return $model->whereIn(
            $model->getKeyName(),
            $keys
        )->cursor();
    }

    public function getTotalCount($results)
    {
        return $results['hits']['total']['value'];
    }

    public function flush($model)
    {
        $model->newQuery()
            ->orderBy($model->getKeyName())
            ->unsearchable();
    }

    public function createIndex($name, array $options = [])
    {
        $this->elasticsearch->indices()->create([
            'index' => $name,
            'body' => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0,
                ]
            ]
        ]);
    }

    public function deleteIndex($name)
    {
        $this->elasticsearch->indices()->delete(['index' => $name]);
    }
} 