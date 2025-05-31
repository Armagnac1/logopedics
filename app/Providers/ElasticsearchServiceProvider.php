<?php

namespace App\Providers;

use App\Services\Scout\ElasticsearchEngine;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Transport\NodePool\NodePool;
use Elastic\Transport\NodePool\NodePoolInterface;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\EngineManager;
use Psr\Http\Client\ClientInterface;

class ElasticsearchServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            $config = config('scout.elasticsearch');
            $host = $config['hosts'][0];

            // Use host.docker.internal when running outside Docker
            $hostName = env('APP_ENV') === 'local' ? 'host.docker.internal' : ($host['host'] ?? 'localhost');

            $connectionString = sprintf(
                '%s://%s:%s',
                $host['scheme'] ?? 'http',
                $hostName,
                $host['port'] ?? '9200'
            );

            return ClientBuilder::create()
                ->setHosts([$connectionString])
                ->setRetries($config['retries'] ?? 3)
                ->build();
        });
    }

    public function boot()
    {
        $this->app->make(EngineManager::class)->extend('elasticsearch', function ($app) {
            return new ElasticsearchEngine(
                $app->make(Client::class)
            );
        });
    }
}
