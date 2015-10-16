<?php

namespace app\src\Parser;

use app\src\Parser\Request\Link\Iledebeute;
use app\src\Parser\Request\Link\Letual;
use app\src\Parser\Request\Link\Rivegauche;
use app\src\Parser\Request\RequestInterface;
use app\src\Parser\Response\Link\LinkParser;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * основной класс парсер данных
 * @author Denis Tikhonov <ozy@mailserver.ru>
 */
class ParserService implements ConfigInterface
{
    /** @var Client */
    private $client;
    /** @var LinkParser */
    private $linkParser;
    /** @var string */
    private $method = 'GET';

    public function __construct()
    {
        $this->client = new Client();
        $this->linkParser = new LinkParser();
    }

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
     * @param string $url
     *
     * @return mixed
     */
    public function collectLLinkData($url)
    {
        $request = new Letual();
        $request->setUrl($url);

        $request = $this->prepareRequest($request);
        $response = $this->request($request);

        return $this->linkParser->convertLLink($response);
    }

    /**
     * @param string $url
     *
     * @return mixed
     */
    public function collectRLinkData($url)
    {
        $request = new Rivegauche();
        $page = $request->getPage();
        $return = [];
        do {
            $response = $this->request($this->prepareRequest($request->setUrl($url)->setPage($page)));
            $data = $this->linkParser->convertRLink($response);

            $i = (empty($data)) ? 0 : 1;// выход из цикла
            $return = array_merge($return, $data);
            $page++;
        } while ($i > 0);

        return $return;
    }

    /**
     * @param string $url
     *
     * @return mixed
     */
    public function collectILinkData($url)
    {
        $request = new Iledebeute();
        $page = $request->getPage();
        $return = [];

        do {
            $response = $this->request($this->prepareRequest($request->setUrl($url)->setPage($page)));
            $data = $this->linkParser->convertILink($response);

            $i = (empty($data)) ? 0 : 1; // выход из цикла
            $return = array_merge($return, $data);
            $page++;
        } while ($i > 0);

        return $return;
    }

    /**
     * @param RequestInterface $request
     *
     * @return Crawler
     *
     * @throws \RuntimeException
     */
    private function request(RequestInterface $request)
    {
        $crawler = $this->client->request($request->getMethod(), $request->getUrl(), $request->toArray());
        return $crawler;
    }

    /**
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    private function prepareRequest(RequestInterface $request)
    {
        $request
            ->setMethod($this->getMethod())
        ;
        return $request;
    }
}
