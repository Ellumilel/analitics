<?php

namespace app\commands;

use app\models\ElizeLink;
use app\models\ElizePrice;
use app\models\ElizeProduct;
use app\models\ErrorProcessing;
use app\models\IledebeauteLink;
use app\models\IledebeautePrice;
use app\models\IledebeauteProduct;
use app\models\LetualLink;
use app\models\LetualPrice;
use app\models\LetualProduct;
use app\models\ParsingStatus;
use app\models\PodruzkaProduct;
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
     * Метод запускается по крон собирает данные по Летуаль
     *
     * @param $offset
     * @param $total
     *
     * @return int
     */
    public function actionLetual($offset, $total)
    {
        $entity = new LetualLink();
        $parsingStatus = new ParsingStatus();
        $parsingStatus->start('letual');
        do {
            $links = $entity->getLinks($offset, 10);

            if (!empty($links) && $offset < $total) {
                foreach ($links as $link) {
                    \Yii::info(sprintf('Обработка: %s ', $link->link), 'cron');
                    //$link->link = 'https://www.letu.ru/uhod-za-kozhei/uhod-za-litsom/ochishchenie/lancome-ochishchayushchii-tonik-dlya-normalnoi-i-smeshannoi-kozhi-tonique-douceur/9269';
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
                    //print_R($result);die;
                    foreach ($result as $res) {
                        if ($res instanceof Response) {
                            if (empty($res->getPrice()) || empty($res->getTitle())) {
                                $this->setDeleted($res, new LetualProduct);
                                \Yii::error(
                                    sprintf('Ошибка обработки: %s : цена или заголовок не найдены', $link->link),
                                    'cron'
                                );
                            } else {
                                $this->saveLetualResult($res);
                            }
                        }
                    }
                    unset($result);
                    unset($crawler);
                }

                $z = 1;
                $offset += 10;
                unset($links);
                unset($client);
            } else {
                $z = 0;
            }
        } while ($z > 0);

        (new LetualProduct())->setDeleted();
        PodruzkaProduct::updatePriceDiff();
        $parsingStatus->end('letual');
        return 0;
    }

    /**
     * Метод запускается по крон собирает данные по Ильдэботэ
     *
     * @param $offset
     * @param $total
     *
     * @return int
     */
    public function actionIledebeaute($offset, $total)
    {
        $entity = new IledebeauteLink();
        $parsingStatus = new ParsingStatus();
        $parsingStatus->start('iledebeaute');

        do {
            $links = $entity->getLinks($offset, 20);

            if (!empty($links) && $offset < $total) {
                foreach ($links as $link) {
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
                    $result = $service->productParse($crawler, ParserService::ILE, $attributes);
                    //print_R($result);die;
                    foreach ($result as $res) {
                        if ($res instanceof Response) {
                            if (empty($res->getArticle()) || empty($res->getTitle())) {
                                \Yii::error(
                                    sprintf('Ошибка обработки: %s : цена или заголовок не найдены', $link->link),
                                    'cron'
                                );
                            } else {
                                $this->saveIledebeauteResult($res);
                            }
                        }
                    }
                    unset($result);
                }
                //die;
                $z = 1;
                $offset += 20;
                unset($links);
                unset($client);
            } else {
                $z = 0;
            }
        } while ($z > 0);
        $parsingStatus->end('iledebeaute');
        (new IledebeauteProduct())->setDeleted();
        PodruzkaProduct::updatePriceDiff();

        return 0;
    }
    /**
     * Метод запускается по крон собирает данные по Элизэ
     *
     * @param $offset
     * @param $total
     *
     * @return int
     */
    public function actionElize($offset, $total)
    {
        $parsingStatus = new ParsingStatus();
        $parsingStatus->start('elize');
        $entity = new ElizeLink();
        do {
            $links = $entity->getLinks($offset, 20);
            if (!empty($links) && $offset < $total) {
                foreach ($links as $link) {
                    //$link->link = 'https://elize.ru/shop/product/3920/';
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
                    $result = $service->productParse($crawler, ParserService::ELI, $attributes);
                    //print_R($result);die;
                    foreach ($result as $res) {
                        if ($res instanceof Response) {
                            if (empty($res->getTitle())) {
                                $this->setDeleted($res, new ElizeProduct);
                                \Yii::error(
                                    sprintf('Ошибка обработки: %s : цена или заголовок не найдены', $link->link),
                                    'cron'
                                );
                            } else {
                                $this->saveElizeResult($res);
                            }
                        }
                    }
                    unset($result);
                }

                $z = 1;
                $offset += 20;
                unset($links);
                unset($client);
            } else {
                $z = 0;
            }
        } while ($z > 0);

        (new ElizeProduct())->setDeleted();
        PodruzkaProduct::updatePriceDiff();
        $parsingStatus->end('elize');
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
        $parsingStatus = new ParsingStatus();
        $parsingStatus->start('rivegauche');

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

                    if (empty($result->getArticle()) ||
                        empty($result->getPrice()) ||
                        empty($result->getTitle())
                    ) {
                        $this->setDeleted($result, new RivegaucheProduct);

                        $errorPr = ErrorProcessing::findOne(['link' => $link->link]);
                        if (!$errorPr) {
                            $errorPr = new ErrorProcessing();
                        }

                        $errorPr->competitor = ErrorProcessing::RIVE;
                        $errorPr->link = $link->link;
                        $errorPr->processing = 0;
                        $errorPr->comment = null;
                        $errorPr->error = 'Ошибка обработки:';
                        if (empty($result->getArticle())) {
                            $errorPr->error .= ' артикул';
                        }
                        if (empty($result->getPrice())) {
                            $errorPr->error .= ' цена';
                        }
                        if (empty($result->getTitle())) {
                            $errorPr->error .= ' заголовок';
                        }
                        $errorPr->error .= ' не найдены';
                        $errorPr->save();
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

                            if (empty($result->getArticle()) ||
                                empty($result->getPrice()) ||
                                empty($result->getTitle())
                            ) {
                                $this->setDeleted($result, new RivegaucheProduct);

                                $errorProcess = ErrorProcessing::findOne(['link' => $url]);
                                if (!$errorProcess) {
                                    $errorProcess = new ErrorProcessing();
                                }

                                $errorProcess->competitor = ErrorProcessing::RIVE;
                                $errorProcess->link = $url;
                                $errorProcess->processing = 0;
                                $errorProcess->comment = null;
                                $errorProcess->error = 'Ошибка обработки:';
                                if (empty($result->getArticle())) {
                                    $errorProcess->error .= ' артикул';
                                }
                                if (empty($result->getPrice())) {
                                    $errorProcess->error .= ' цена';
                                }
                                if (empty($result->getTitle())) {
                                    $errorProcess->error .= ' заголовок';
                                }
                                $errorProcess->error .= ' не найдены';
                                $errorProcess->save();
                            } else {
                                $this->saveResult($result);
                            }
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
        $parsingStatus->end('rivegauche');
        PodruzkaProduct::updatePriceDiff();
        return 0;
    }

    /**
     * Метод проверяет собранные артикула
     */
    public function actionErrorProcessing()
    {
        $offset = 0;
        /** @var $entity ErrorProcessing */
        $entity = new ErrorProcessing();
        do {
            $links = $entity->getLinks($offset, 20);
            if (!empty($links)) {
                foreach ($links as $link) {
                    if (!$link->processing) {
                        $crawler = $this->getData($link->link);

                        if (!$crawler) {
                            continue;
                        }

                        $service = new ParserService();
                        $result = $service->checkProduct($crawler, ParserService::RIV);

                        if (!empty($result)) {
                            $errorProcess = ErrorProcessing::findOne(['link' => $link->link]);
                            $errorProcess->comment = $result;
                            $errorProcess->processing = 1;
                            $errorProcess->save();
                        }
                    }
                    //print_r($link->link);
                    unset($node);
                    unset($service);
                    unset($result);
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
            $product->deleted_at = '0000-00-00 00:00:00';
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

        //print_r($product->attributes);die;
        if (!empty($result->getShowcasesPromotext())) {
            $product->showcases_promotext = $result->getShowcasesPromotext();
        }
        //print_r($product->attributes);die;
        $product->new_price = $result->getNewPrice();
        $product->old_price = $result->getPrice();

        try {
            $lPrice = new LetualPrice();
            $lPrice->article = $result->getArticle();

            if ($result->getPrice()) {
                $lPrice->old_price = $result->getPrice();
            }

            if ($result->getNewPrice()) {
                $lPrice->new_price = $result->getNewPrice();
            }

            $product->deleted_at = '0000-00-00 00:00:00';

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
     * @param Response $result
     */
    private function saveIledebeauteResult(Response $result)
    {
        $product = IledebeauteProduct::findOne(['article' => $result->getArticle()]);

        if (!$product) {
            $product = new IledebeauteProduct();
        }

        $product->attributes = $result->toArray();
        $product->new_price = $result->getNewPrice();
        $product->old_price = $result->getPrice();

        try {
            $iPrice = new IledebeautePrice();
            $iPrice->article = $result->getArticle();

            if ($result->getPrice()) {
                $iPrice->old_price = $result->getPrice();
            }

            if ($result->getNewPrice()) {
                $iPrice->new_price = $result->getNewPrice();
            }

            $product->deleted_at = '0000-00-00 00:00:00';

            if ($product->save()) {
                if (!empty($iPrice->new_price) || !empty($iPrice->new_price)) {
                    $iPrice->save();
                }
            } else {
                \Yii::error(
                    sprintf(
                        'Ошибка сохранения артикула Ile: %s data: %s',
                        $result->getArticle(),
                        json_encode($result->toArray())
                    ),
                    'cron'
                );
            }
        } catch (\Exception $e) {
            \Yii::error(
                sprintf(
                    'Exception %s сохранения артикула Ile %s data: %s',
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
    private function saveElizeResult(Response $result)
    {
        $product = ElizeProduct::findOne(['article' => $result->getArticle()]);

        if (!$product) {
            try {
                $product = ElizeProduct::findOne(['title' => $result->getTitle(), 'link' => $result->getLink()]);
            } catch (\Exception $e) {
                //todo
            }

            if ($product instanceof ElizeProduct && is_int($product->article)) {
                $product->article = $result->getArticle();
            } else {
                $product = new ElizeProduct();
            }
        }

        $product->attributes = $result->toArray();
        $product->new_price = !empty($result->getNewPrice()) ? $result->getNewPrice() : null;
        $product->old_price = !empty($result->getPrice()) ? $result->getPrice() : null;
        $product->deleted_at = '0000-00-00 00:00:00';

        try {
            $ePrice = new ElizePrice();
            $ePrice->article = $result->getArticle();
            $ePrice->old_price = $product->old_price;
            $ePrice->new_price = $product->new_price;

            if ($product->save()) {
                    $ePrice->save();
            } else {
                \Yii::error(
                    sprintf(
                        'Ошибка сохранения артикула E: %s data: %s',
                        $result->getArticle(),
                        json_encode($result->toArray())
                    ),
                    'cron'
                );
            }
        } catch (\Exception $e) {
            \Yii::error(
                sprintf(
                    'Exception %s сохранения артикула E %s data: %s',
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
        $client->getClient()->setDefaultOption('verify', false);
        $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_TIMEOUT, 10);
        $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_FOLLOWLOCATION, true);
        $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_MAXREDIRS, 2);
        //Ожидание до подключения
        $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_CONNECTTIMEOUT, 10);
        $client->setClient($guzzle);

        try {
            $crawler = $client->request('GET', $url);
        } catch (\Exception $e) {
            \Yii::error(sprintf('Ошибка обработки: %s %s ', $e->getMessage(), $url), 'cron');
            return null;
        }

        return $crawler;
    }

    /**
     * @param Response $result
     * @param \yii\db\ActiveRecord $entity
     */
    private function setDeleted(Response $result, \yii\db\ActiveRecord $entity)
    {
        $product = $entity::findOne(['article' => $result->getArticle()]);

        if (!$product) {
            return;
        } else {
            $product->deleted_at = (new \DateTime())->format('Y-m-d H:i:s');
            $product->save();
        }

        return;
    }
}
