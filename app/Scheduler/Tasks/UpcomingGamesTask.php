<?php

require_once dirname(__DIR__) . '/bootstrap.php';

$igdb = $container->igdb;
$config = $container->config;
$cronitor = $container->cronitor;
$mediawiki = $container->mediawiki;

$schedule->run(function () use ($igdb, $config, $cronitor, $mediawiki) {
    $memcached = new \Memcached;
    $memcached->setOption(Memcached::OPT_BINARY_PROTOCOL, true);
    $memcached->addServer(
        $config->get('cache.memcached.ip'),
        $config->get('cache.memcached.port')
    );
    
    if (in_array(env('APP_ENV'), ['production', 'staging'])) {
        $memcached->setSaslAuthData(env('MEMCACHEDCLOUD_USERNAME'), env('MEMCACHEDCLOUD_PASSWORD'));
    }
    
    if ($memcached->get('IGDB')) {
        exit;
    }
    
    $memcached->set('IGDB', true);
    
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
    
    $cronitor->complete();
    
    if ($igdb) {
        $igdb->map(function ($game) use ($mediawiki) {
            $game->is_article = $mediawiki->isArticle($game->name);
            
            return $game;
        });
    }
    
    $memcached->replace('IGDB', $igdb, 82800);
})
->dailyAt('05:00');

return $schedule;
