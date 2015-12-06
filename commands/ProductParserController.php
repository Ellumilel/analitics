<?php

namespace app\commands;

use app\models\LetualLink;
use app\models\LetualPrice;
use app\models\LetualProduct;
use app\models\RivegaucheLink;
use app\models\RivegauchePrice;
use app\models\RivegaucheProduct;
use app\src\Parser\ParserService;
use app\src\Parser\Response\Response;
use yii\console\Controller;
use Goutte\Client;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 *
 * Class ProductParserController
 *
 * @package app\commands
 */
class ProductParserController extends Controller
{
    /**
     * Метод запускается по крон собирает данные по РивГош
     *
     * @return int
     */
    public function actionLetual()
    {
        $entity = new LetualLink();
        $offset = 0;
        do {
            $links = $entity->getLinks($offset, 20);
            if (!empty($links)) {
                foreach ($links as $link) {
                    \Yii::info(sprintf('Обработка: %s ', $link->link), 'cron');
                    $link->link='http://www.letu.ru/makiyazh/dlya-glaz/tush/lancome-tush-dlya-resnits-grandiose/16900021';
                    $crawler = $this->getData($link->link);
                    if (!$crawler) {
                        \Yii::error(
                            sprintf('Не удалось получить страницу: %s ', $link->link),
                            'cron'
                        );
                        continue;
                    }
                    $attributes = [
                        'link' => $link->link,
                        'group' => $link->group,
                        'category' => $link->category,
                        'sub_category' => $link->sub_category,
                    ];

                    $service = new ParserService();
                    $result = $service->productParse($crawler, ParserService::LET, $attributes);

                    foreach ($result as $res) {
                        if ($res instanceof Response) {
                            if (empty($res->getPrice()) || empty($res->getTitle())) {
                                \Yii::error(
                                    sprintf('Ошибка обработки: %s : цена или заголовок не найдены', $link->link),
                                    'cron'
                                );
                                continue;
                            } else {
                                $this->saveLetualResult($res);
                            }
                            continue;
                        } else {
                            continue;
                        }
                    }
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
     * Метод запускается по крон собирает данные по РивГош
     * @param $offset
     * @param $total
     *
     * @return int
     */
    public function actionRivegauche($offset, $total)
    {
        /** @var $entity RivegaucheLink */
        $entity = new RivegaucheLink();
        do {
            $links = $entity->getLinks($offset, 20);
            if (!empty($links) && $offset < $total) {
                foreach ($links as $link) {
                    \Yii::info(sprintf('Обработка: %s ', $link->link), 'cron');
                    $crawler = $this->getData($link->link);
                    if (!$crawler) {
                        continue;
                    }
                    $attributes = [
                        'link' => $link->link,
                        'group' => $link->group,
                        'category' => $link->category,
                        'sub_category' => $link->sub_category,
                    ];

                    $service = new ParserService();
                    $result = $service->productParse($crawler, ParserService::RIV, $attributes);

                    if (empty($result->getPrice()) || empty($result->getTitle())) {
                        \Yii::error(
                            sprintf('Ошибка обработки: %s : цена или заголовок не найдены', $link->link),
                            'cron'
                        );
                        continue;
                    } else {
                        $this->saveResult($result);
                    }

                    foreach ($result->getUrls() as $url) {
                        $attributes = [
                            'link' => $url,
                            'group' => $link->group,
                            'category' => $link->category,
                            'sub_category' => $link->sub_category,
                        ];

                        $crawler = $this->getData($url);
                        if ($crawler) {
                            $service = new ParserService();
                            $result = $service->productParse($crawler, ParserService::RIV, $attributes);
                            $this->saveResult($result);
                        }
                    }
                    unset($node);
                    unset($service);
                    unset($result);
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
     * @param Response $result
     */
    private function saveResult(Response $result)
    {
        $product = RivegaucheProduct::findOne(['article' => $result->getArticle()]);
        if (!$product) {
            $product = new RivegaucheProduct();
        }
        $product->attributes = $result->toArray();

        try {
            $rPrice = new RivegauchePrice();
            $rPrice->article = $result->getArticle();

            if ($result->getGoldPrice()) {
                $rPrice->gold_price = $result->getGoldPrice();
            }

            if ($result->getBluePrice()) {
                $rPrice->blue_price = $result->getBluePrice();
            }

            if ($result->getPrice()) {
                $rPrice->price = $result->getPrice();
            }

            if ($product->save()) {
                if (!empty($rPrice->price) || !empty($rPrice->blue_price) || !empty($rPrice->gold_price)) {
                    $rPrice->save();
                }
            } else {
                \Yii::error(
                    sprintf(
                        'Ошибка сохранения артикула R: %s data: %s',
                        $result->getArticle(),
                        json_encode($result->toArray())
                    ),
                    'cron'
                );
            }
        } catch (\Exception $e) {
            \Yii::error(
                sprintf(
                    'Exception %s сохранения артикула R %s data: %s',
                    $e->getMessage(),
                    $result->getArticle(),
                    json_encode($result->toArray())
                ),
                'cron'
            );
        }
    }

    /**
     * @param Response $result
     */
    private function saveLetualResult(Response $result)
    {
        $product = LetualProduct::findOne(['article' => $result->getArticle()]);

        if (!$product) {
            $product = new LetualProduct();
        }
        $product->attributes = $result->toArray();
        $product->new_price = $result->getPrice();

        try {
            $lPrice = new LetualPrice();
            $lPrice->article = $result->getArticle();

            if ($result->getPrice()) {
                $lPrice->old_price = $result->getPrice();
            }

            if ($result->getNewPrice()) {
                $lPrice->new_price = $result->getNewPrice();
            }

            if ($product->save()) {
                if (!empty($lPrice->new_price) || !empty($lPrice->new_price)) {
                    $lPrice->save();
                }
            } else {
                \Yii::error(
                    sprintf(
                        'Ошибка сохранения артикула L: %s data: %s',
                        $result->getArticle(),
                        json_encode($result->toArray())
                    ),
                    'cron'
                );
            }
        } catch (\Exception $e) {
            \Yii::error(
                sprintf(
                    'Exception %s сохранения артикула L %s data: %s',
                    $e->getMessage(),
                    $result->getArticle(),
                    json_encode($result->toArray())
                ),
                'cron'
            );
        }
    }

    /**
     * @param string $url
     *
     * @return null|\Symfony\Component\DomCrawler\Crawler
     */
    private function getData($url)
    {
        $client = new Client();
        $guzzle = $client->getClient();
        //Использование Прокси пока отключено
        //$client->getClient()->setDefaultOption('config/curl/'.CURLOPT_PROXY, 'http://141.101.118.147:80');
        //Максимальное количество секунд выполнения запроса
        $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_TIMEOUT, 10);
        //Ожидание до подключения
        $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_CONNECTTIMEOUT, 15);
        $client->setClient($guzzle);

        try {
            $crawler = $client->request('GET', $url);
        } catch (\Exception $e) {
            \Yii::error(sprintf('Ошибка обработки: %s %s ', $e->getMessage(), $url), 'cron');
            return null;
        }

        return $crawler;
    }
}
