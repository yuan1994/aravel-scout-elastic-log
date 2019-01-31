<?php

namespace Yuan1994\Scout\Elasticsearch;

use Laravel\Scout\EngineManager;
use Illuminate\Support\ServiceProvider;
use ScoutEngines\Elasticsearch\ElasticsearchEngine;
use Elasticsearch\ClientBuilder as ElasticBuilder;

class ElasticsearchProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->singleton('elastic', function() {
           $elasticBuilder =  ElasticBuilder::create();
           // 根据配置是否开启日志记录
           if (config('scout.elasticsearch.log')) {
               $elasticBuilder->setLogger(app('log'));
           }

           return $elasticBuilder
               ->setHosts(config('scout.elasticsearch.hosts'))
               ->build();
        });

        app(EngineManager::class)->extend('elasticsearch', function($app) {
            return new ElasticsearchEngine(
                app('elastic'),
                config('scout.elasticsearch.index')
            );
        });
    }
}
