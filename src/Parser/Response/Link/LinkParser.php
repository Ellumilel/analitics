<?php

namespace app\src\Parser\Response\Link;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Парсер ответов
 *
 * @author Denis Tikhonov <ozy@mailserver.ru>
 */
class LinkParser
{
    /**
     * @param Crawler $response
     *
     * @return array
     */
    public function convertLLink(Crawler $response)
    {
        $urls = $response->filter('div.productItemDescription h3.title a')->each(
            function ($node) {
                $href = $node->attr('href');
                $url = sprintf('http://www.letu.ru%s', substr($href, 0, strpos($href, ";")));

                return $url;
            }
        );

        return $urls;
    }

    /**
     * @param Crawler $response
     *
     * @return array
     */
    public function convertELink(Crawler $response)
    {
        $urls = $response->filter('div.productBlock div.product_item a.type')->each(
            function ($node) {
                $href = $node->attr('href');
                $url = sprintf('https://elize.ru/%s', $href);

                return $url;
            }
        );

        return $urls;
    }

    /**
     * @param Crawler $response
     *
     * @return array
     */
    public function convertRLink(Crawler $response)
    {
        $urls = $response->filter('ul.es_page_type div.es_product_images a')->each(
            function ($node) {
                $href = $node->attr('href');
                $url = sprintf('http://shop.rivegauche.ru%s', $href);

                return $url;
            }
        );

        if (empty($urls)) {
            $urls = $response->filter('ul.es_page_type div.es_product_name a')->each(
                function ($node) {
                    $href = $node->attr('href');
                    $url = sprintf('http://shop.rivegauche.ru%s', $href);

                    return $url;
                }
            );
        }

        return $urls;
    }

    /**
     * @param Crawler $response
     *
     * @return array
     */
    public function convertILink(Crawler $response)
    {
        $urls = $response->filter('div.b-showcase__item p.b-showcase__item__link a')->each(
            function ($node) {
                $href = $node->attr('href');
                $url = sprintf('http://iledebeaute.ru%s', $href);

                return $url;
            }
        );

        return $urls;
    }
}
