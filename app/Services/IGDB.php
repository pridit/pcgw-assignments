<?php

namespace App\Services;

class IGDB
{
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Perform a new request to the IGDB API
     *
     * @param  string  $url
     * @param  array   $opts
     * @param  boolean $companies
     * @return boolean
     */
    protected function request($url, $opts = null, $companies = false)
    {
        $url = $this->config->get('api.igdb.endpoint') . $url;

        if($opts) {
            $optUrl = array();

            foreach ($opts as $param => $paramValue) {
                if ($param == "filters") {
                    foreach ($paramValue as $filter => $filterValue) {
                        array_push($optUrl, "filter[" . $filter . "]=" . $filterValue);
                    }
                } else {
                    array_push($optUrl, $param . "=" . $paramValue);
                }
            }

            $url .= "?" . implode("&", $optUrl);
        }

        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "user-key: " . $this->config->get('api.igdb.key') . "\r\n" .
                            "Accept: application/json"

            )
        );

        $context = stream_context_create($opts);
        $file = @file_get_contents($url, false, $context);

        if ($file) {
            return json_decode($file);
        }

        return false;
    }

    /**
     * Perform a games search as the request and curate the result set
     *
     * @param  array $opts
     * @return array
     */
    public function gamesSearch($opts)
    {
        $result = self::request('games/', $opts);

        foreach ($result as $gameKey => $game) {
            foreach ($game->release_dates as $release) {
                if ($release->platform == $this->config->get('api.igdb.parameters.platform')) $result[$gameKey]->release_dates = $release;
            }

            if (!empty($game->developers)) {
                foreach ($game->developers as $developerKey => $developer) {
                    $result[$gameKey]->developers[] = (array) $this->companySearch($developer)[0];
                    if (is_integer($developer)) unset($result[$gameKey]->developers[$developerKey]);
                }
            }

            if (!empty($game->publishers)) {
                foreach ($game->publishers as $publisherKey => $publisher) {
                    $result[$gameKey]->publishers[] = (array) $this->companySearch($publisher)[0];
                    if (is_integer($publisher)) unset($result[$gameKey]->publishers[$publisherKey]);
                }
            }
        }

        uasort($result, function ($item1, $item2) {
            return $item1->release_dates->date > $item2->release_dates->date;
        });

        return collect($result);
    }

    /**
     * Perform a company search as the request
     *
     * @param  integer $id
     * @return array
     */
    public function companySearch($id)
    {
        return self::request('companies/' . $id, ['fields' => 'name']);
    }
}
