<?php

namespace Smartpush;

use stdClass;

class Push
{
    private $endpoint = 'http://api.getmo.com.br';

    private $alias;
    private $devid;
    private $when;
    private $prod = 1;
    private $notifications = [];
    private $filter;

    private $data;

    public function __construct($devid, $when = 'now', $alias = '')
    {
        $this->devid = $devid;
        $this->when = $when; // timestamp, 0000-00-00 00:00:00, 00/00/0000 00:00:00 (recomendo usar timestamp para agendar um envio)
        $this->alias = $alias;

        $this->filter = new stdClass;
        $this->filter->type = "TAG";
        $this->filter->rules = [];

        return $this;
    }

    public function setEnvironment($env = '1')
    {
        $this->prod = in_array($env, ['1', 'prod', 'production']) ? 1 : 0;

        return $this;
    }

    public function addNotification($appid, $platform, $params)
    {
        if (!in_array($platform, ['iOS', 'IOS', 'ANDROID', 'WINDOWS', 'CHROME', 'SAFARI', 'FIREFOX'])) {
            return false;
        }

        if ($platform == 'IOS') {
            $platform = 'iOS';
        }

        if (!is_object($params)) {
            if (is_array($params)) {
                $params = json_decode(json_encode($params));
            } else {
                return false;
            }
        }

        $notification = new stdClass;
        $notification->appid = $appid;
        $notification->platform = $platform;
        $notification->params = $params;

        array_push($this->notifications, $notification);

        return $this;
    }

    public function getNotifications()
    {
        return (isset($this->notifications) && $this->notifications) ? $this->filter->rules : false;
    }

    public function addTag($key, $valueOrOperator, $value = false)
    {
        if ($value) {
            $operator = $valueOrOperator == 'EQ' ? '=' : $valueOrOperator;
        } else {
            $operator = '=';
            $value = $valueOrOperator;
        }

        array_push($this->filter->rules, [$key, $operator, $value]);

        return $this;
    }

    public function getTags()
    {
        return (isset($this->filter->rules) && $this->filter->rules) ? $this->filter->rules : false;
    }

    public function getPayload($toJson = true)
    {
        if (!$this->validate()) {
            return false;
        }

        if ($toJson) {
            return json_encode($this->makePayload());
        } else {
            return $this->makePayload();
        }
    }

    public function send()
    {
        if (!$this->validate()) {
            return false;
        }

        $this->data = $this->post('/push', $this->makePayload());

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getPushInfo($pushid)
    {
        return $this->get('/'.$this->devid.'/'.$pushid);
    }

    public function cancelPush($pushid)
    {
        return $this->put('/'.$this->devid.'/'.$pushid.'/cancel');
    }

    private function validate()
    {
        if (!count($this->notifications)) {
            return false;
        }

        if (!count($this->filter->rules)) {
            return false;
        }

        return true;
    }

    private function makePayload()
    {
        $payload = new stdClass;
        $payload->alias = $this->alias;
        $payload->when = $this->when;
        $payload->devid = $this->devid;
        $payload->prod = $this->prod;

        $payload->notifications = $this->notifications;

        $payload->filter = $this->filter;

        return $payload;
    }

    private function post($route, $payload = [])
    {
        return $this->curl('POST', $route, $payload);
    }

    private function get($route)
    {
        return $this->curl('GET', $route);
    }

    private function put($route)
    {
        return $this->curl('PUT', $route);
    }

    private function curl($verb, $route, $payload = [])
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $this->endpoint.$route);

        switch($verb) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, 'data='.urlencode(json_encode($payload)));
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                break;
        }

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
}
