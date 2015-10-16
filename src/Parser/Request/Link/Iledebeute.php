<?php

namespace app\src\Parser\Request\Link;

use app\src\Parser\Request\RequestInterface;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 */
class Iledebeute implements RequestInterface
{
    /** @var string */
    private $method;
    /** @var string */
    private $url;
    /** @var int */
    private $page = 1;
    /** @var int */
    private $perPage = 36;

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
        return $this->url.sprintf('page%d/?perpage=%d', $this->getPage(), $this->getPerPage());
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
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     *
     * @return $this
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     *
     * @return $this
     */
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return [
        ];
    }
}
