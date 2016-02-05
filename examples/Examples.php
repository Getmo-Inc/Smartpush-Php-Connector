<?php

require_once '../src/Push.php';

use Smartpush\Push;

class Examples
{
    public function example1()
    {
        $push = new Push('CN6Z8Eka3FSQ9IA');
        $push->addNotification('000000000000001', 'ANDROID', $this->getParams());
        $push->addTag('DEVICE_UUID', '353317056832026');
        $push->send();
        $data = $push->getData();

        var_dump($data);
    }

    public function example2()
    {
        $data = (new Push('CN6Z8Eka3FSQ9IA'))
            ->addNotification('000000000000001', 'ANDROID', $this->getParams())
            ->addTag('DEVICE_UUID', '353317056832026')
            ->send()
            ->getData();

        var_dump($data);
    }

    public function example3()
    {
        $data = (new \Smartpush\Push('CN6Z8Eka3FSQ9IA'))
            ->addNotification('000000000000001', 'ANDROID', $this->getParams())
            ->addTag('DEVICE_UUID', '353317056832026')
            ->send()
            ->getData();

        var_dump($data);
    }

    public function example4()
    {
        $payload = (new Push('CN6Z8Eka3FSQ9IA'))
            ->addNotification('000000000000001', 'ANDROID', $this->getParams())
            ->addTag('DEVICE_UUID', '353317056832026')
            ->getPayload(); // return json

        var_dump($payload);
    }

    public function example5()
    {
        $payload = (new Push('CN6Z8Eka3FSQ9IA'))
            ->addNotification('000000000000001', 'ANDROID', $this->getParams())
            ->addTag('DEVICE_UUID', '353317056832026')
            ->getPayload(false); // return object

        var_dump($payload);
    }

    private function getParams()
    {
        $params = new stdClass;
        $params->adtype = "PUSH_BANNER_AD";
        $params->adnetwork = "smartpush";
        $params->title = "Buscapé";
        $params->detail = "Te protege em R$ 5.000,00";
        $params->category = "14";
        $params->banner = "http://admin.getmo.com.br/assets/img/demo/rsz_teprotege.png";
        $params->url = "buscape://com.buscapecompany/search?productId=548829&utm_source=alertadepreco&utm_medium=push&utm_campaign=548829";
        $params->video = "9x2knWZY0hM";

        return $params;
    }
}