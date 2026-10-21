<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

/**
 * Client HTTP pour appeler l’API distante Dikitivi.
 *
 * @author Xanders
 * @see https://www.xsam-tech.com
 */
class ApiClientManager
{
    /**
     * Exécute un appel API et retourne le corps JSON décodé.
     *
     * @param  string  $method  Méthode HTTP (GET, POST, …)
     * @param  string  $url  URL complète de l’endpoint
     * @param  string|null  $api_token  Bearer token optionnel
     * @param  array  $data_to_send  Données form_params
     * @param  string|null  $current_ip_address  IP à transmettre en header
     * @param  int|string|null  $current_user_id  ID utilisateur à transmettre en header
     * @return object|null
     */
    public static function call($method, $url, $api_token = null, $data_to_send = [], $current_ip_address = null, $current_user_id = null)
    {
        $client = new Client();

        $headers = [
            'Authorization' => 'Bearer ' . $api_token,
            'Accept' => 'application/json',
            'X-localization' => ! empty(Session::get('locale')) ? Session::get('locale') : App::getLocale(),
        ];

        if ($current_ip_address !== null) {
            $headers['X-ip-address'] = $current_ip_address;
        }

        if ($current_user_id !== null) {
            $headers['X-user-id'] = $current_user_id;
        }

        try {
            $response = $client->request($method, $url, [
                'headers' => $headers,
                'form_params' => $data_to_send,
                'verify' => true,
            ]);

            return json_decode($response->getBody(), false);
        } catch (ClientException $e) {
            return json_decode($e->getResponse()->getBody()->getContents(), false);
        }
    }
}
