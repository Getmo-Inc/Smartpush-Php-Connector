<?php

namespace Smartpush;

use Smartpush\Services\Http AS HttpService;

class Notification extends HttpService
{
    private $devid;
    private $appid;
    private $platform;

    public function __construct($devid, $appid, $platform)
    {
        $this->devid = $devid;
        $this->appid = $appid;
        $this->platform = $platform;

        return $this;
    }

    public function getLastNotifications($hwid, $optional = false)
    {
        return $this->getNotifications('/notifications/last', $hwid, $optional);
    }

    public function getLastUnreadNotifications($hwid, $optional = false)
    {
        return $this->getNotifications('/notifications/unread', $hwid, $optional);
    }

    public function getExtraPayload($pushid)
    {
        return $this->post('notifications/extra', [
            'devid' => $this->devid,
            'appid' => $this->appid,
            'pushid' => $pushid,
        ]);
    }

    public function readOneNotification($hwid, $pushid)
    {
        return $this->put('notifications/read-one', [
            'devid' => $this->devid,
            'appid' => $this->appid,
            'hwid' => $hwid,
            'pushid' => $pushid,
        ]);
    }

    public function readAllNotifications($hwid)
    {
        return $this->put('notifications/read-one', [
            'devid' => $this->devid,
            'appid' => $this->appid,
            'hwid' => $hwid,
        ]);
    }

    public function hideHwidNotification($hwid, $pushid)
    {
        return $this->delete('notifications/hide', [
            'devid' => $this->devid,
            'appid' => $this->appid,
            'hwid' => $hwid,
            'pushid' => $pushid,
        ]);
    }

    private function getNotifications($route, $hwid, $optional = false)
    {
        $payload = [
            'devid' => $this->devid,
            'appid' => $this->appid,
            'platform' => $this->platform,
            'hwid' => $hwid,
        ];

        if ($optional) {
            if (isset($optional['show'])) {
                $payload['show'] = $optional['show'];
            }
            if (isset($optional['startingDate'])) {
                $payload['startingDate'] = $optional['startingDate'];
            }
            if (isset($optional['dateFormat'])) {
                $payload['dateFormat'] = $optional['dateFormat'];
            }
        }

        return $this->post($route, $payload);
    }
}