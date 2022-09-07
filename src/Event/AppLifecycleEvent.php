<?php

namespace BAL\AppLifecycleBundle\Event;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class AppLifecycleEvent extends Event
{
    protected array $data = [];
    protected Response $response;
    protected string $appVersion;
    protected string $shopEventId;
    protected string $shopId;
    protected string $shopUrl;

    public function getData() : array
    {
        return $this->data;
    }

    public function setData(array $data) : AppLifecycleEvent
    {
        $this->data = $data;

        return $this;
    }

    public function getResponse() : Response
    {
        return $this->response;
    }

    public function setResponse(Response $response) : AppLifecycleEvent
    {
        $this->response = $response;

        return $this;
    }

    public function getAppVersion() : string
    {
        return $this->appVersion;
    }

    public function setAppVersion(string $appVersion) : AppLifecycleEvent
    {
        $this->appVersion = $appVersion;

        return $this;
    }

    public function getShopEventId() : string
    {
        return $this->shopEventId;
    }

    public function setShopEventId(string $shopEventId) : AppLifecycleEvent
    {
        $this->shopEventId = $shopEventId;

        return $this;
    }

    public function getShopId() : string
    {
        return $this->shopId;
    }

    public function setShopId(string $shopId) : AppLifecycleEvent
    {
        $this->shopId = $shopId;

        return $this;
    }

    public function getShopUrl() : string
    {
        return $this->shopUrl;
    }

    public function setShopUrl(string $shopUrl) : AppLifecycleEvent
    {
        $this->shopUrl = $shopUrl;

        return $this;
    }

    public function assign(array $data, bool $useWithMethod = false) : static
    {
        $object = $this;
        foreach ($data as $key => $value) {
            if (method_exists($object, ($method = 'set' . ucfirst($key)))) {
                $object->$method($value);
            } elseif ($useWithMethod && method_exists($object, ($method = 'with' . ucfirst($key)))) {
                $object = $object->$method($value);
            } else {
                try {
                    $this->$key = $value;
                } catch (\Error|\Exception $error) {
                    // nth
                }
            }
        }

        return $object;
    }

}
