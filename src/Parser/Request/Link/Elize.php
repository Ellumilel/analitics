<?php

namespace app\src\Parser\Request\Link;

use app\src\Parser\Request\RequestInterface;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 */
class Elize implements RequestInterface
{
    /** @var string */
    private $method;
    /** @var string */
    private $url;

    /**
     * @inheritdoc
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @inheritdoc
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @inheritdoc
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return [
            'verify' => true,
        ];
    }
}
