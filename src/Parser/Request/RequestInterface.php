<?php

namespace app\src\Parser\Request;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 */
interface RequestInterface
{
    /**
     * @return $this
     */
    public function getMethod();
    /**
     * @param string $method
     *
     * @return $this
     */
    public function setMethod($method);
    /**
     * @return $this
     */
    public function getUrl();
    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url);
    /**
     * @return array
     */
    public function toArray();
}
