<?php

namespace app\commands;

use app\models\IledebeauteLink;
use app\models\IledebeautePrice;
use app\models\IledebeauteProduct;
use Symfony\Component\DomCrawler\Crawler;
use yii\console\Controller;
use Goutte\Client;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 *
 * Class GetArticle
 *
 * @package app\commands
 */
class IledebeauteProductController extends Controller
{
    /** @var string */
    private $url = 'http://iledebeaute.ru';

    /**
     * @return int
     */
    public function actionIndex()
    {
        /** @var $entity IledebeauteLink */
        $entity = new IledebeauteLink();
        $offset = 0;

        do {
            $links = $entity->getLinks($offset, 20);

            if (!empty($links)) {
                foreach ($links as $link) {
                    $client = new Client();
                    /** @var Crawler $crawler */
                    $crawler = $client->request('GET', $link['link']);
                    $head = $this->getHead($crawler);
                    $body = $this->getBody($crawler, $head['brand']);

                    //TODO Обработка
                    if (empty($head) || empty($body)) {
                       //todo логирование
                    } else {
                        $result = $head + $body;
                    }

                    if (!empty($result)) {
                        $this->saveResult($result, $link);
                    }

                    unset($node);
                    unset($subNode);
                    unset($head);
                }

                $z = 1;
                $offset += 20;
                unset($links);
                unset($client);

            } else {
                $z = 0;
            }
        } while ($z > 0);

        return 0;
    }

    /**
     * Выбирает заголовки и бренд
     *
     * @param $crawler
     *
     * @return array
     */
    private function getHead($crawler)
    {
        $head = $crawler->filter('div.b-product__bigColumn p.title')->each(
            function ($node) {
                $brand = $node->filter('a.title__brand')->each(
                    function ($subNode) {
                        return $subNode->text();
                    }
                );

                $title = $node->text();
                $title = str_replace('\r', '', $title);
                $title = str_replace('\n', '', $title);
                $title = str_replace($brand, '', $title);
                $title = trim($title);
                $title = str_replace(' ', '', $title);

                return [
                    'brand' => reset($brand),
                    'title' => $title,
                ];
            }
        );

        if (empty(reset($head))) {
            $head = $crawler->filter('div.b-product__item__promo__content')->each(
                function ($node) {
                    $title = $node->filter('div.b-product__item__promo__title h1')->each(
                        function ($subNode) {
                            return $subNode->text();
                        }
                    );

                    $brand = $node->filter('div.b-product__item__promo__logo a')->each(
                        function ($subNode) {
                            $brand = $subNode->attr('href');
                            $brand = str_replace('/shop/brands/', '', $brand);
                            $brand = str_replace('/', '', $brand);
                            return $brand;
                        }
                    );
                    $title = $this->clearTitle($title);
                    return [
                        'brand' => reset($brand),
                        'title' => $title,
                    ];
                }
            );
        }

        if (empty(reset($head))) {
            $head = $crawler->filter('div.b-product__item__item')->each(
                function ($node) {
                    $title = $node->filter('div.b-product__item__item__title h1')->each(
                        function ($subNode) {
                            return $subNode->text();
                        }
                    );

                    $brand = $node->filter('div.b-product__item__item__logo a')->each(
                        function ($subNode) {
                            $brand = $subNode->attr('href');
                            $brand = str_replace('/shop/brands/', '', $brand);
                            $brand = str_replace('/', '', $brand);
                            return $brand;
                        }
                    );
                    $title = $this->clearTitle($title);
                    return [
                        'brand' => reset($brand),
                        'title' => $title,
                    ];
                }
            );
        }

        return reset($head);
    }

    /**
     * Выбирает тело с основным набором параметров
     *
     * @param Crawler $crawler
     * @param string $brand
     *
     * @return array
     */
    private function getBody(Crawler $crawler, $brand = '')
    {
        $body = $crawler->filter('table.b-showcase')->each(
            function ($node) {
                $items = $node->filter('tr.b-showcase__item')->each(
                    function ($sub) {
                        $price = $sub->filter('td.b-showcase__item__price')->each(
                            function ($sub) {
                                $oldPrice = $sub->filter('span.old')->each(
                                    function ($sub) {
                                        return $sub->text();
                                    }
                                );
                                $newPrice = $sub->filter('span.new')->each(
                                    function ($sub) {
                                        return $sub->text();
                                    }
                                );

                                if (empty(reset($oldPrice)) && empty(reset($newPrice))) {
                                    $newPrice = $sub->filter('div.vip_price span.def')->each(
                                        function ($sub) {
                                            return $sub->text();
                                        }
                                    );
                                    $oldPrice = $sub->filter('div.full_price span.def')->each(
                                        function ($sub) {
                                            return $sub->text();
                                        }
                                    );
                                }

                                $newPrice = $this->clearPrice($newPrice);
                                $oldPrice = $this->clearPrice($oldPrice);

                                return [
                                    'oldPrice' => (empty($oldPrice)) ? $newPrice : $oldPrice,
                                    'newPrice' => $newPrice,
                                ];
                            }
                        );

                        $article = $sub->filter('td.b-showcase__item__descr p.b-showcase__item__brand')->each(
                            function ($sub) {
                                return $sub->text();
                            }
                        );
                        $image = $sub->filter('td.b-showcase__item__img img')->each(
                            function ($sub) {
                                return 'http:'.$sub->attr('src');
                            }
                        );
                        $description = $sub->filter('td.b-showcase__item__descr p.b-showcase__item__link')->each(
                            function ($sub) {
                                return $sub->text();
                            }
                        );

                        $showcases_new = $sub->filter('td.b-showcase__item__tags span.new')->each(
                            function ($sub) {
                                return $sub->text();
                            }
                        );

                        $showcases_sale = $sub->filter('td.b-showcase__item__tags span.sale')->each(
                            function ($sub) {
                                return $sub->text();
                            }
                        );

                        $showcases_exclusive = $sub->filter('td.b-showcase__item__tags span.exclusive')->each(
                            function ($sub) {
                                return $sub->text();
                            }
                        );

                        $showcases_limit = $sub->filter('td.b-showcase__item__tags span.limit')->each(
                            function ($sub) {
                                return $sub->text();
                            }
                        );

                        $showcases_best = $sub->filter('td.b-showcase__item__tags span.best')->each(
                            function ($sub) {
                                return $sub->text();
                            }
                        );

                        if (!empty(reset($description))) {
                            $description = $this->clearDescription($description);
                        }

                        if (empty(reset($article)) && empty(reset($price))) {
                            return [];
                        }

                        return [
                            'price' => reset($price),
                            'article' => reset($article),
                            'image' => reset($image),
                            'description' => $description,
                            'showcases_new' => !empty(reset($showcases_new)) ? 1 : 0,
                            'showcases_sale' => !empty(reset($showcases_sale)) ? 1 : 0,
                            'showcases_exclusive' => !empty(reset($showcases_exclusive)) ? 1 : 0,
                            'showcases_limit' => !empty(reset($showcases_limit)) ? 1 : 0,
                            'showcases_best' => !empty(reset($showcases_best)) ? 1 : 0,
                        ];
                    }
                );
                $result['items'] = $items;
                return $result;
            }
        );

        if (empty(reset($body))) {
            $body = $crawler->filter('table.b-showcase')->each(
                function ($node) {
                    $items = $node->filter('tr.b-cart__showcase__item')->each(
                        function ($sub) {
                            $price = $sub->filter('td.b-cart__showcase__item__price')->each(
                                function ($sub) {
                                    $oldPrice = $sub->filter('span.old')->each(
                                        function ($sub) {
                                            return $sub->text();
                                        }
                                    );

                                    $newPrice = $sub->filter('span.new')->each(
                                        function ($sub) {
                                            return $sub->text();
                                        }
                                    );

                                    if (empty(reset($oldPrice)) && empty(reset($newPrice))) {
                                        $newPrice = $sub->filter('span.def')->each(
                                            function ($sub) {
                                                return $sub->text();
                                            }
                                        );
                                    }

                                    $newPrice = $this->clearPrice($newPrice);
                                    $oldPrice = $this->clearPrice($oldPrice);

                                    return [
                                        'oldPrice' => (empty($oldPrice)) ? $newPrice : $oldPrice,
                                        'newPrice' => $newPrice,
                                    ];
                                }
                            );

                            $article = $sub->filter('td p.b-cart__showcase__item__brand')->each(
                                function (
                                    $sub
                                ) {
                                    return $sub->text();
                                }
                            );
                            $image = $sub->filter('td.b-cart__showcase__item__img img')->each(
                                function ($sub) {
                                    return 'http:'.$sub->attr('src');
                                }
                            );
                            $description = $sub->filter('td.b-showcase__item__descr p.b-showcase__item__link')->each(
                                function (
                                    $sub
                                ) {
                                    return $sub->text();
                                }
                            );
                            $description = trim(reset($description));
                            $description = str_replace(' ', '', $description);
                            $description = str_replace('*', '', $description);
                            $description = str_replace('\r', '', $description);
                            $description = str_replace('\n', '', $description);
                            $description = trim($description);

                            $showcases_new = $sub->filter('td.b-showcase__item__tags span.new')->each(
                                function ($sub) {
                                    return $sub->text();
                                }
                            );

                            $showcases_sale = $sub->filter('td.b-showcase__item__tags span.sale')->each(
                                function ($sub) {
                                    return $sub->text();
                                }
                            );

                            $showcases_exclusive = $sub->filter('td.b-showcase__item__tags span.exclusive')->each(
                                function ($sub) {
                                    return $sub->text();
                                }
                            );

                            $showcases_limit = $sub->filter('td.b-showcase__item__tags span.limit')->each(
                                function ($sub) {
                                    return $sub->text();
                                }
                            );

                            $showcases_best = $sub->filter('td.b-showcase__item__tags span.best')->each(
                                function ($sub) {
                                    return $sub->text();
                                }
                            );
                            return [
                                'price' => reset($price),
                                'article' => reset($article),
                                'image' => reset($image),
                                'description' => $description,
                                'showcases_new' => !empty(reset($showcases_new)) ? 1 : 0,
                                'showcases_sale' => !empty(reset($showcases_sale)) ? 1 : 0,
                                'showcases_exclusive' => !empty(reset($showcases_exclusive)) ? 1 : 0,
                                'showcases_limit' => !empty(reset($showcases_limit)) ? 1 : 0,
                                'showcases_best' => !empty(reset($showcases_best)) ? 1 : 0,
                            ];
                        }
                    );
                    $result['items'] = $items;
                    return $result;
                }
            );
        }

        if (empty(reset($body))) {
            $body = $crawler->filter('table.b-cart__showcase_'.$brand)->each(
                function ($node) {
                    $items = $node->filter('tr.b-cart__showcase__item')->each(
                        function ($sub) {
                            $price = $sub->filter('td.b-cart__showcase__item__price')->each(
                                function ($sub) {
                                    $oldPrice = $sub->filter('span.old')->each(
                                        function ($sub) {
                                            return $sub->text();
                                        }
                                    );

                                    $newPrice = $sub->filter('span.new')->each(
                                        function ($sub) {
                                            return $sub->text();
                                        }
                                    );

                                    if (empty(reset($oldPrice)) && empty(reset($newPrice))) {
                                        $newPrice = $sub->filter('span.def')->each(
                                            function ($sub) {
                                                return $sub->text();
                                            }
                                        );
                                    }

                                    $newPrice = $this->clearPrice($newPrice);
                                    $oldPrice = $this->clearPrice($oldPrice);

                                    return [
                                        'oldPrice' => (empty($oldPrice)) ? $newPrice : $oldPrice,
                                        'newPrice' => $newPrice,
                                    ];
                                }
                            );

                            $article = $sub->filter('td p.b-cart__showcase__item__brand')->each(
                                function (
                                    $sub
                                ) {
                                    return $sub->text();
                                }
                            );
                            $image = $sub->filter('td.b-cart__showcase__item__img img')->each(
                                function ($sub) {
                                    return 'http:'.$sub->attr('src');
                                }
                            );
                            $description = $sub->filter('td.b-showcase__item__descr p.b-showcase__item__link')->each(
                                function (
                                    $sub
                                ) {
                                    return $sub->text();
                                }
                            );
                            $description = trim(reset($description));
                            $description = str_replace(' ', '', $description);
                            $description = str_replace('*', '', $description);
                            $description = str_replace('\r', '', $description);
                            $description = str_replace('\n', '', $description);
                            $description = trim($description);

                            $showcases_new = $sub->filter('td.b-showcase__item__tags span.new')->each(
                                function ($sub) {
                                    return $sub->text();
                                }
                            );

                            $showcases_sale = $sub->filter('td.b-showcase__item__tags span.sale')->each(
                                function ($sub) {
                                    return $sub->text();
                                }
                            );

                            $showcases_exclusive = $sub->filter('td.b-showcase__item__tags span.exclusive')->each(
                                function ($sub) {
                                    return $sub->text();
                                }
                            );

                            $showcases_limit = $sub->filter('td.b-showcase__item__tags span.limit')->each(
                                function ($sub) {
                                    return $sub->text();
                                }
                            );

                            $showcases_best = $sub->filter('td.b-showcase__item__tags span.best')->each(
                                function ($sub) {
                                    return $sub->text();
                                }
                            );

                            return [
                                'price' => reset($price),
                                'article' => reset($article),
                                'image' => reset($image),
                                'description' => $description,
                                'showcases_new' => !empty(reset($showcases_new)) ? 1 : 0,
                                'showcases_sale' => !empty(reset($showcases_sale)) ? 1 : 0,
                                'showcases_exclusive' => !empty(reset($showcases_exclusive)) ? 1 : 0,
                                'showcases_limit' => !empty(reset($showcases_limit)) ? 1 : 0,
                                'showcases_best' => !empty(reset($showcases_best)) ? 1 : 0,
                            ];
                        }
                    );
                    $result['items'] = $items;
                    return $result;
                }
            );
        }
        return reset($body);
    }

    /**
     * Чистит заголовок
     *
     * @param array $title
     *
     * @return string
     */
    private function clearTitle($title)
    {
        $title = reset($title);
        $title = str_replace('\r', '', $title);
        $title = str_replace('\n', '', $title);
        $title = trim($title);
        $title = str_replace(' ', '', $title);
        $title = str_replace('</span>', '', $title);
        $title = str_replace('<span>', '', $title);

        return (string)$title;
    }

    /**
     * Чистит цену
     *
     * @param $price
     *
     * @return int
     */
    private function clearPrice($price)
    {
        $price = reset($price);
        $price = str_replace(' руб.', '', $price);
        $price = str_replace(' ', '', $price);
        $price = trim($price);
        $price = str_replace(' ', '', $price);
        $price = str_replace('*', '', $price);
        $price = str_replace('\r', '', $price);
        $price = str_replace('\n', '', $price);
        $price = nl2br($price);
        $price = str_replace('<br />', '', $price);
        $price = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $price);
        $price = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $price);
        return (int)$price;
    }

    /**
     * Чистит описание
     *
     * @param $description
     *
     * @return string
     */
    private function clearDescription($description)
    {
        $description = reset($description);
        $description = trim($description);
        //$description = str_replace(' ', '', $description);
        $description = str_replace('*', '', $description);
        $description = str_replace('\r', '', $description);
        $description = str_replace('\n', '', $description);
        $description = nl2br($description);
        $description = str_replace('<br />', '', $description);
        //$description = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $description);
        $description = str_replace(array("\r\n", "\r", "\n", "\t"), '', $description);
        return (string)$description;
    }

    /**
     * В рамках получения брендов пытаемся устранить косяки с заполнением
     *
     * @param string $brand
     *
     * @return string
     */
    private function clearBrand($brand)
    {
        switch ($brand) {
            case 'D&G':
                $brand = 'DOLCE & GABBANA';
                break;
            case 'Dolce&Gabbana Make Up':
                $brand = 'DOLCE & GABBANA';
                break;
            case 'Dolce&Gabbana':
                $brand = 'DOLCE & GABBANA';
                break;
        }

        $brand = strtoupper($brand);
        return $brand;
    }

    /**
     * Сохраняем результаты из массива
     *
     * @param $result
     * @param $link
     */
    private function saveResult($result, $link)
    {
        foreach ($result['items'] as $item) {
            if (!empty($item['article'])) {

                $product = IledebeauteProduct::findOne(['article' => $item['article']]);
                if (!$product) {
                    $product = new IledebeauteProduct();
                }

                $product->brand = $this->clearBrand($result['brand']);
                $product->title = $result['title'];
                $product->article = $item['article'];
                $product->showcases_new = $item['showcases_new'];
                $product->showcases_sale = $item['showcases_sale'];
                $product->showcases_exclusive = $item['showcases_exclusive'];
                $product->showcases_limit = $item['showcases_limit'];
                $product->showcases_best = $item['showcases_best'];
                $product->new_price = $this->getPrice($item['price']['newPrice']);
                $product->old_price = $this->getPrice($item['price']['oldPrice']);
                $product->category = $link['category'];
                $product->group = $link['group'];
                $product->link = $link['link'];
                $product->sub_category = $link['sub_category'];
                $product->image_link = $item['image'];
                $product->description = $item['description'];

                $price = new IledebeautePrice();
                $price->article = $item['article'];

                $price->new_price = $this->getPrice($item['price']['newPrice']);
                $price->old_price = $this->getPrice($item['price']['oldPrice']);

                if (!empty($price) && $product->save()) {
                    $price->save();
                }
            }
        }
    }

    /**
     * Получаем либо цену либо 0
     *
     * @param $price
     *
     * @return float
     */
    private function getPrice($price)
    {
        $price = (float) $price;

        if (!empty($price) && is_float($price)) {
            return (float) $price;
        }

        return (float) 0;
    }
}
