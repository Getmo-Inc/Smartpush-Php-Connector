<?php

namespace Smartpush\Services;

class Http
{
    private $endpoint = 'http://api.getmo.com.br';

    public function get($route)
    {
        return $this->curl('GET', $route);
    }

    public function post($route, $payload = [])
    {
        return $this->curl('POST', $route, $payload);
    }

    public function put($route, $payload = [])
    {
        return $this->curl('PUT', $route, $payload);
    }

    public function delete($route, $payload = [])
    {
        return $this->curl('DELETE', $route, $payload);
    }

    private function curl($verb, $route, $payload = [])
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->endpoint.$route
        ]);

        switch($verb) {
            case 'POST':
                $json = json_encode($payload);
                curl_setopt_array($ch, [
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => $json,
                    CURLOPT_HEADER => 1,
                    CURLOPT_HTTPHEADER => [
                        'Content-Type:application/json',
                        'Content-Length: ' . strlen($json)
                    ]
                ]);
                break;
            case 'PUT':
            case 'DELETE':
                $json = json_encode($payload);
                curl_setopt_array($ch, [
                    CURLOPT_CUSTOMREQUEST => $verb,
                    CURLOPT_POSTFIELDS => $json,
                    CURLOPT_HEADER => 1,
                    CURLOPT_HTTPHEADER => [
                        'Content-Type:application/json',
                        'Content-Length: ' . strlen($json)
                    ]
                ]);
                break;
        }

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
}
