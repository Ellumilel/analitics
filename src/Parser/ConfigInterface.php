<?php

namespace app\src\Parser;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 */
interface ConfigInterface
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
}
