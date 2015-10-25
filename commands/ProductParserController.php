<?php

namespace app\commands;

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
    public function actionRivegauche()
    {
        /** @var $entity RivegaucheLink */
        $entity = new RivegaucheLink();
        $offset = 0;
        do {
            $links = $entity->getLinks($offset, 5);
            if (!empty($links)) {
                foreach ($links as $link) {
                    $crawler = $this->riveGetData($link->link);
                    if (!$crawler) {
                        break;
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
                        break;
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

                        $crawler = $this->riveGetData($url);
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
     * @param string $url
     *
     * @return null|\Symfony\Component\DomCrawler\Crawler
     */
    private function riveGetData($url)
    {
        $client = new Client();
        $guzzle = $client->getClient();
        //Использование Прокси пока отключено
        //$client->getClient()->setDefaultOption('config/curl/'.CURLOPT_PROXY, 'http://141.101.118.147:80');
        //Максимальное количество секунд выполнения запроса
        $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_TIMEOUT, 10);
        //Ожидание до подключения
        $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_CONNECTTIMEOUT, 30);
        $client->setClient($guzzle);

        try {
            $crawler = $client->request('GET', $url);
        } catch (\Exception $e) {
            \Yii::error(sprintf('Ошибка обработки: %s ', $url), 'cron');
            return null;
        }

        return $crawler;
    }
}
