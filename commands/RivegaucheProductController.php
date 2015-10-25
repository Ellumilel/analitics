<?php
namespace app\commands;

use app\models\RivegaucheLink;
use app\models\RivegauchePrice;
use app\models\RivegaucheProduct;
use yii\console\Controller;
use Goutte\Client;

/**
 * @deprecated Уже не используется
 *
 * @author Denis Tikhonov <ozy@mailserver.ru>
 *
 * Class RivegaucheProductController
 *
 * @package app\commands
 */
class RivegaucheProductController extends Controller
{
    /** @var string */
    private $url = 'http://shop.rivegauche.ru';
    /**
     * @return int
     */
    private $proxyList = [
        'http://141.101.118.147:80',
        'http://213.85.92.10:80',
        'http://104.28.4.57:80',
        'http://64.15.205.101:80',
        'http://208.48.81.133:80',
        'http://208.91.197.104:80',
        'http://104.28.8.165:80',
        'http://104.28.12.100:80',
        'http://104.28.17.74:80',
        'http://108.162.197.200:80',
        'http://104.28.7.184:80',
    ];
    public function actionIndex()
    {
        /** @var $entity RivegaucheLink */
        $entity = new RivegaucheLink();
        $offset = 0;
        do {
            $links = $entity->getLinks($offset, 5);
            if (!empty($links)) {
                foreach ($links as $link) {
                    \Yii::info(sprintf('Обрабатываем: %s ',$link['link']),'cron');

                    $client = new Client();
                    $guzzle = $client->getClient();

                    $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_PROXY, 'http://141.101.118.147:80');
                    //$client->getClient()->setDefaultOption('config/curl/'.CURLOPT_TIMEOUT, 10);
                    $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_CONNECTTIMEOUT, 10);
                    $client->setClient($guzzle);
                    /*$guzzle = $client->getClient();
                    $guzzle->setDefaultOption('timeout', 10);

                    $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_TIMEOUT_MS, 100);
                    $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_CONNECTTIMEOUT, 5);
                    $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_RETURNTRANSFER, true);
                    $client->setClient($guzzle);*/
                    $crawler = $client->request('GET', $link['link']);

                    \Yii::info(sprintf('Извлекаем тело: %s ',$link['link']),'cron');
                    $head = $this->getHtml($crawler, true);
                    \Yii::info(sprintf('HEAD тело: %s ',$link['link']),'cron');
                    if (!empty($head['links'])) {
                        foreach ($head['links'] as $l) {
                            $crawler = $client->request('GET', $l);
                            $subHead = $this->getHtml($crawler, false);
                            $subHead['link'] = $l;
                            $this->saveResult($subHead, $link);
                        }
                    }
                    if(empty($head['title'])) {
                        $head = $this->getPromoHTML($crawler, true);
                    }
                    if(empty($head['title'])) {
                        $head = $this->getPromo2HTML($crawler, true);
                    }
                    $head['link'] = $link['link'];
                    $this->saveResult($head, $link);
                    unset($node);
                    unset($subNode);
                    unset($head);
                }
                $z = 1;
                $offset += 5;
                unset($links);
                unset($client);
            } else {
                $z = 0;
            }
        } while ($z > 0);

        return 0;
    }

    /**
     * @param $crawler
     * @param bool|true $widthLinks
     *
     * @return mixed
     */
    private function getHtml($crawler, $widthLinks = true)
    {
        $head = $crawler->filter('div.es_product')->each(function ($node) {
            $title = $node->filter('div.es_right_full_name h1')->each(function ($subNode) {
                return $subNode->text();
            });

            $links = $node->filter('div.es_right_price ul a')->each(function ($subNode) {
                return $this->url.$subNode->attr('href');
            });

            $brand = $node->filter('div.es_right_lable img')->each(function ($subNode) {
                $brand = $subNode->attr('alt');
                $brand = str_replace(' Logo Image', '', $brand);
                $brand = trim($brand);

                return $brand;
            });

            $description = $node->filter('div.es_right_price_type')->each(function ($subNode) {

                $description = trim($subNode->text());
                $description = str_replace(' ', '', $description);
                $description = str_replace('*', '', $description);
                $description = str_replace('\r', '', $description);
                $description = str_replace('\n', '', $description);
                $description = nl2br($description);
                $description = str_replace('<br />', '', $description);
                $description = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $description);
                $description = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $description);

                return $description;
            });

            $price = $node->filter('div.es_right_price_all_price')->each(function ($subNode) {
                $goldPrice = $subNode->filter('span.gold_price')->each(function ($subNode) {
                    return $subNode->text();
                });
                $bluePrice = $subNode->filter('span.blue_price')->each(function ($subNode) {
                    return $subNode->text();
                });
                $price = $subNode->filter('span.price')->each(function ($subNode) {
                    return $subNode->text();
                });
                $fixPrice = $subNode->filter('div.fix-price')->each(function ($subNode) {
                    return $subNode->text();
                });

                $bluePrice = $this->clearPrice($bluePrice);
                $goldPrice = $this->clearPrice($goldPrice);
                $price = $this->clearPrice($price);
                $fixPrice = $this->clearPrice($fixPrice);

                return [
                    'gold_price' => $goldPrice,
                    'blue_price' => $bluePrice,
                    'price' => (!empty($price)) ? $price : $fixPrice,
                ];
            });

            $imageLink = $node->filter('div#primary_image img')->each(function ($subNode) {
                return $subNode->attr('src');
            });

            $showcasesOffer = $node->filter('div.showcases_offer')->each(function ($subNode) {
                return $subNode;
            });

            $showcasesNew = $node->filter('div.showcases_new')->each(function ($subNode) {
                return $subNode;
            });

            $showcasesExclusive = $node->filter('div.showcases_exclusive')->each(function ($subNode) {
                return $subNode;
            });

            $showcasesCompliment = $node->filter('div.showcases_compliment')->each(function ($subNode) {
                return $subNode;
            });

            $showcasesBestsellers = $node->filter('div.showcases_bestsellers')->each(function ($subNode) {
                return $subNode;
            });

            $showcasesExpertiza = $node->filter('div.showcases_expertiza')->each(function ($subNode) {
                return $subNode;
            });

            return [
                'title' => reset($title),
                'links' => $links,
                'brand' => reset($brand),
                'price' => reset($price),
                'description' => reset($description),
                'image_link' => reset($imageLink),
                'showcases_offer' => !empty(reset($showcasesOffer)) ? 1 : 0,
                'showcases_new' => !empty(reset($showcasesNew)) ? 1 : 0,
                'showcases_exclusive' => !empty(reset($showcasesExclusive)) ? 1 : 0,
                'showcases_compliment' => !empty(reset($showcasesCompliment)) ? 1 : 0,
                'showcases_bestsellers' => !empty(reset($showcasesBestsellers)) ? 1 : 0,
                'showcases_expertiza' => !empty(reset($showcasesExpertiza)) ? 1 : 0,
            ];
        });

        $return = reset($head);
        if (!$widthLinks) {
            $return['links'] = [];
        }

        return $return;
    }

    /**
     * @param $crawler
     * @param bool|true $widthLinks
     *
     * @return mixed
     */
    private function getPromoHTML($crawler, $widthLinks = true)
    {
        $head = $crawler->filter('div.es_product')->each(function ($node) {
            $title = $node->filter('div.es_right div.dior_product_category h1')->each(function ($subNode) {
                return $subNode->text();
            });

            $links = $node->filter('div.es_right_price_group ul a')->each(function ($subNode) {
                return $this->url.$subNode->attr('href');
            });

            $brand = $node->filter('div.es_right_lable img')->each(function ($subNode) {
                $brand = $subNode->attr('alt');
                $brand = str_replace(' Logo Image', '', $brand);
                $brand = trim($brand);

                return $brand;
            });

            $description = $node->filter('div.prod_add_to_cart td.leftalign')->each(function ($subNode) {

                $description = trim($subNode->text());
                $description = str_replace(' ', '', $description);
                $description = str_replace('*', '', $description);
                $description = str_replace('\r', '', $description);
                $description = str_replace('\n', '', $description);
                $description = nl2br($description);
                $description = str_replace('<br />', '', $description);
                $description = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $description);
                $description = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $description);

                return $description;
            });

            $price = $node->filter('td span.price')->each(function ($subNode) {
                $goldPrice = $subNode->filter('span.gold_price')->each(function ($subNode) {
                    return $subNode->text();
                });
                $bluePrice = $subNode->filter('span.blue_price')->each(function ($subNode) {
                    return $subNode->text();
                });
                $price = $subNode->filter('div.card-price span.price')->each(function ($subNode) {
                    return $subNode->text();
                });
                $fixPrice = $subNode->filter('div.fix-price')->each(function ($subNode) {
                    return $subNode->text();
                });

                $bluePrice = $this->clearPrice($bluePrice);
                $goldPrice = $this->clearPrice($goldPrice);
                $price = $this->clearPrice($price);
                $fixPrice = $this->clearPrice($fixPrice);

                return [
                    'gold_price' => $goldPrice,
                    'blue_price' => $bluePrice,
                    'price' => (!empty($price)) ? $price : $fixPrice,
                ];
            });

            $imageLink = $node->filter('div#primary_image img')->each(function ($subNode) {
                return $subNode->attr('src');
            });

            $showcasesOffer = $node->filter('div.showcases_offer')->each(function ($subNode) {
                return $subNode;
            });

            $showcasesNew = $node->filter('div.showcases_new')->each(function ($subNode) {
                return $subNode;
            });

            $showcasesExclusive = $node->filter('div.showcases_exclusive')->each(function ($subNode) {
                return $subNode;
            });

            $showcasesCompliment = $node->filter('div.showcases_compliment')->each(function ($subNode) {
                return $subNode;
            });

            $showcasesBestsellers = $node->filter('div.showcases_bestsellers')->each(function ($subNode) {
                return $subNode;
            });

            $showcasesExpertiza = $node->filter('div.showcases_expertiza')->each(function ($subNode) {
                return $subNode;
            });

            return [
                'title' => reset($title),
                'links' => $links,
                'brand' => reset($brand),
                'price' => reset($price),
                'description' => reset($description),
                'image_link' => reset($imageLink),
                'showcases_offer' => !empty(reset($showcasesOffer)) ? 1 : 0,
                'showcases_new' => !empty(reset($showcasesNew)) ? 1 : 0,
                'showcases_exclusive' => !empty(reset($showcasesExclusive)) ? 1 : 0,
                'showcases_compliment' => !empty(reset($showcasesCompliment)) ? 1 : 0,
                'showcases_bestsellers' => !empty(reset($showcasesBestsellers)) ? 1 : 0,
                'showcases_expertiza' => !empty(reset($showcasesExpertiza)) ? 1 : 0,
            ];
        });

        $return = reset($head);
        if (!$widthLinks) {
            $return['links'] = [];
        }

        return $return;
    }

    /**
     * @param $crawler
     * @param bool|true $widthLinks
     *
     * @return mixed
     */
    private function getPromo2HTML($crawler, $widthLinks = true)
    {
        $head = $crawler->filter('div.es_product')->each(function ($node) {
            $title = $node->filter('div.es_right div.dior_product_category h1')->each(function ($subNode) {
                return $subNode->text();
            });

            $links = $node->filter('div.es_right_price_group ul a')->each(function ($subNode) {
                return $this->url.$subNode->attr('href');
            });

            $brand = $node->filter('div.es_right_lable img')->each(function ($subNode) {
                $brand = $subNode->attr('alt');
                $brand = str_replace(' Logo Image', '', $brand);
                $brand = trim($brand);

                return $brand;
            });

            $description = $node->filter('div.prod_add_to_cart td.leftalign')->each(function ($subNode) {

                $description = trim($subNode->text());
                $description = str_replace(' ', '', $description);
                $description = str_replace('*', '', $description);
                $description = str_replace('\r', '', $description);
                $description = str_replace('\n', '', $description);
                $description = nl2br($description);
                $description = str_replace('<br />', '', $description);
                $description = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $description);
                $description = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $description);

                return $description;
            });

            $price = $node->filter('td span.price')->each(function ($subNode) {
                $goldPrice = $subNode->filter('span.gold_price')->each(function ($subNode) {
                    return $subNode->text();
                });
                $bluePrice = $subNode->filter('span.blue_price')->each(function ($subNode) {
                    return $subNode->text();
                });
                $price = $subNode->filter('div.card-price span.price')->each(function ($subNode) {
                    return $subNode->text();
                });
                $fixPrice = $subNode->filter('div.fix-price')->each(function ($subNode) {
                    return $subNode->text();
                });

                $bluePrice = $this->clearPrice($bluePrice);
                $goldPrice = $this->clearPrice($goldPrice);
                $price = $this->clearPrice($price);
                $fixPrice = $this->clearPrice($fixPrice);

                return [
                    'gold_price' => $goldPrice,
                    'blue_price' => $bluePrice,
                    'price' => (!empty($price)) ? $price : $fixPrice,
                ];
            });

            $imageLink = $node->filter('div#primary_image img')->each(function ($subNode) {
                return $subNode->attr('src');
            });

            $showcasesOffer = $node->filter('div.showcases_offer')->each(function ($subNode) {
                return $subNode;
            });

            $showcasesNew = $node->filter('div.showcases_new')->each(function ($subNode) {
                return $subNode;
            });

            $showcasesExclusive = $node->filter('div.showcases_exclusive')->each(function ($subNode) {
                return $subNode;
            });

            $showcasesCompliment = $node->filter('div.showcases_compliment')->each(function ($subNode) {
                return $subNode;
            });

            $showcasesBestsellers = $node->filter('div.showcases_bestsellers')->each(function ($subNode) {
                return $subNode;
            });

            $showcasesExpertiza = $node->filter('div.showcases_expertiza')->each(function ($subNode) {
                return $subNode;
            });

            return [
                'title' => reset($title),
                'links' => $links,
                'brand' => reset($brand),
                'price' => reset($price),
                'description' => reset($description),
                'image_link' => reset($imageLink),
                'showcases_offer' => !empty(reset($showcasesOffer)) ? 1 : 0,
                'showcases_new' => !empty(reset($showcasesNew)) ? 1 : 0,
                'showcases_exclusive' => !empty(reset($showcasesExclusive)) ? 1 : 0,
                'showcases_compliment' => !empty(reset($showcasesCompliment)) ? 1 : 0,
                'showcases_bestsellers' => !empty(reset($showcasesBestsellers)) ? 1 : 0,
                'showcases_expertiza' => !empty(reset($showcasesExpertiza)) ? 1 : 0,
            ];
        });

        $return = reset($head);
        if (!$widthLinks) {
            $return['links'] = [];
        }

        return $return;
    }

    /**
     * @param $result
     * @param $link
     */
    private function saveResult($result, $link)
    {
        \Yii::info(sprintf('Сохраняем тело: %s ',json_encode($result)),'cron');
        if(empty($result) || empty($result['link']) || empty($result['title']) || empty($result['category'])) {
            return;
        } else {
            \Yii::error('Ошибка сохранения артикула R: '.json_encode($result),'cron');
        }
        preg_match('/[0-9]+$/i', $result['link'], $data);
        $article = $data[0];
        unset($data);

        $product = RivegaucheProduct::findOne(['article' => $article]);
        if (!$product) {
            $product = new RivegaucheProduct();
        }

        $product->brand = $this->clearBrand($result);
        $product->title = $result['title'];
        $product->article = $article;
        $product->category = $link['category'];
        $product->group = $link['group'];
        $product->link = $result['link'];
        $product->sub_category = $link['sub_category'];
        $product->image_link = !empty($result['image_link']) ? $result['image_link'] : '';
        $product->description = !empty($result['description']) ? $result['description'] : '';
        $product->showcases_offer = $result['showcases_offer'];
        $product->showcases_new = $result['showcases_new'];
        $product->showcases_exclusive = $result['showcases_exclusive'];
        $product->showcases_compliment = $result['showcases_compliment'];
        $product->showcases_bestsellers = $result['showcases_bestsellers'];
        $product->showcases_expertiza = $result['showcases_expertiza'];
        $product->gold_price = $this->getPrice($result['price']['gold_price']);
        $product->blue_price = $this->getPrice($result['price']['blue_price']);
        $product->price = $this->getPrice($result['price']['price']);
        try {
            $rPrice = new RivegauchePrice();
            $rPrice->article = $article;

            $rPrice->gold_price = $this->getPrice($result['price']['gold_price']);
            $rPrice->blue_price = $this->getPrice($result['price']['blue_price']);
            $rPrice->price = $this->getPrice($result['price']['price']);


            if ($product->save()) {
                $rPrice->save();
            } else {
                \Yii::error('Ошибка сохранения артикула R: '.json_encode($result),'cron');
            }
        } catch (\Exception $e) {
            \Yii::error(sprintf('Exception %s сохранения артикула R: %s',$e->getMessage(), json_encode($result)),'cron');
        }
    }

    /**
     * Чистит цену для РивГош
     *
     * @param $price
     *
     * @return int
     */
    private function clearPrice($price)
    {
        $price = reset($price);
        $price = str_replace(' Р', '', $price);
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
     * В рамках получения брендов пытаемся устранить косяки с заполнением
     *
     * @param array $result
     *
     * @return string
     */
    private function clearBrand($result)
    {
        if (!empty($result['brand'])) {
            switch ($result['brand']) {
                case "L'OREAL":
                    $result['brand'] = 'LOREAL';
                    break;
                case "COLORAMA":
                    $result['brand'] = 'MAYBELLINE';
                    break;
            }

            $result['brand'] = strtoupper($result['brand']);
        }

        return $result;
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
