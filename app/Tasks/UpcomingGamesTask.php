<?php

use App\Helpers\IGDB;
use App\Helpers\Cache;

use Crunz\Schedule;

$dotenv = new Dotenv\Dotenv(__DIR__ . '/../../');
$dotenv->load();

$config = new \Noodlehaus\Config(dirname(__DIR__) . '/../config/api.php');

$schedule = new Schedule();

$schedule->run(function () use ($config) {
    $igdb = new IGDB($config);
    $cache = new Cache($config);
    $cronitor = new \Cronitor\Client($config->get('api.cronitor.monitor'), $config->get('api.cronitor.authkey'));

    $cronitor->run();

    $igdb = $igdb->gamesSearch([
        'fields'                                => implode($config->get('api.igdb.parameters.fields'), ','),
        'filter[first_release_date][gte]'       => date('Y-m-d'),
        'filter[first_release_date][lt]'        => date('Y-m-t', strtotime('+2 months')),
        'filter[release_dates.platform][eq]'    => $config->get('api.igdb.parameters.platform'),
        'filter[release_dates.region][eq]'      => $config->get('api.igdb.parameters.region')[0],
        'filter[release_dates.region][eq]'      => $config->get('api.igdb.parameters.region')[1],
        'filter[hypes][gt]'                     => $config->get('api.igdb.parameters.hypes'),
        'order'                                 => $config->get('api.igdb.parameters.order'),
        'limit'                                 => $config->get('api.igdb.parameters.limit')
    ]);

    $cache->set('IGDB', $igdb);

    $cronitor->complete();
})
->dailyAt('05:00');

return $schedule;
