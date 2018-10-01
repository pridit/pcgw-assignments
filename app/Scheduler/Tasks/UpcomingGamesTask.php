<?php

require_once dirname(__DIR__) . '/bootstrap.php';

$igdb = $container->igdb;
$config = $container->config;
$cronitor = $container->cronitor;
$mediawiki = $container->mediawiki;

$container->crunz->run(function () use ($igdb, $config, $cronitor, $mediawiki) {
    $memcached = new \Memcached;
    $memcached->addServer(
        $config->get('cache.memcached.ip'),
        $config->get('cache.memcached.port')
    );
    
    $cronitor->run();
    
    $igdb = $igdb->gamesSearch([
        'fields'                                => implode($config->get('api.igdb.parameters.fields'), ','),
        'filter[first_release_date][gte]'       => date('Y-m-d'),
        'filter[first_release_date][not_eq]'    => date('Y-m-t'),
        'filter[first_release_date][lt]'        => date('Y-m-t', strtotime('+2 months')),
        'filter[release_dates.platform][eq]'    => $config->get('api.igdb.parameters.platform'),
        'filter[release_dates.region][eq]'      => $config->get('api.igdb.parameters.region')[0],
        'filter[release_dates.region][eq]'      => $config->get('api.igdb.parameters.region')[1],
        'filter[hypes][gt]'                     => $config->get('api.igdb.parameters.hypes'),
        'order'                                 => $config->get('api.igdb.parameters.order'),
        'limit'                                 => $config->get('api.igdb.parameters.limit')
    ]);
    
    $igdb->map(function ($game) use ($mediawiki) {
        $game->is_article = $mediawiki->isArticle($game->name);
        
        return $game;
    });
    
    $memcached->set('IGDB', $igdb);
    
    $cronitor->complete();
})
->dailyAt('05:00');

return $container->crunz;
