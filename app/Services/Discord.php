<?php

namespace App\Services;

class Discord
{
    protected $message;

    public function __construct($config) {
        $this->config = $config;
    }

    /**
     * Send a new message to Discord via cURL
     *
     * @param  array $message
     * @return boolean
     */
    public function send($message) {
        if($this->config->get('webhook.discord.endpoint')) {
            $data = [
                'embeds' => [$message],
            ];

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $this->config->get('webhook.discord.endpoint'));
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

            $output = curl_exec($curl);
            $output = json_decode($output, true);

            if (curl_getinfo($curl, CURLINFO_HTTP_CODE) != 204) {
                throw new \Exception($output['message']);
            }

            curl_close($curl);

            return true;
        }

        return false;
    }
}
