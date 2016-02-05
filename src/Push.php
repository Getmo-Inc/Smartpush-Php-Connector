<?php

namespace Smartpush;

use stdClass;

class Push
{
    private $endpoint = 'http://api.getmo.com.br/push';

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

    public function setEnvironment($env = 'prod')
    {
        $this->prod = in_array($env, ['1', 'prod', 'production']) ? 1 : 0;

        return $this;
    }

    public function addNotification($appid, $platform, $params)
    {
        if (!in_array($platform, ['iOS', 'ANDROID', 'WINDOWS'])) {
            return false;
        }

        if (!is_object($params)) {
            return false;
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
        return $this->notifications;
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
        return $this->filter->rules;
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

        $this->request($this->makePayload());

        return $this;
    }

    public function getData()
    {
        return $this->data;
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

    private function request($payload)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $this->endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'data='.urlencode(json_encode($payload)));

        $data = curl_exec($ch);
        curl_close($ch);

        $this->data = $data;
    }
}
