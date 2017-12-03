<?php

namespace app\commands;

use app\models\ParsingStatus;
use app\models\PodruzkaProduct;
use app\models\RivegaucheCategory;
use app\models\RivegaucheProduct;
use app\src\Parser\ParserService;
use app\src\Parser\Response\Response;
use yii\console\Controller;
use Goutte\Client;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 * Class RivParserController
 * @package app\commands
 */
class RivController extends Controller
{
    /**
     * Метод запускается по крон собирает данные по РивГош, новый сайт
     * @return int
     */
    public function actionParseApi()
    {
        $parsingStatus = new ParsingStatus();
        $parsingStatus->start('rivegauche');

        $categoryList = RivegaucheCategory::find()->all();
        foreach ($categoryList as $category) {
            $i = 0;
            do {
                /*if ($category->type == 'category') {
                    $z = 0;
                    continue;
                }*/
                switch ($category->type) {
                    case "category":
                        $result = $this->getData(
                            'https://shop.rivegauche.ru/rest/v1/rivegauche/products?pageSize=30&query=%3A%3Acategory%3A'
                            .$category->link.'&currentPage='.$i
                        );
                        break;
                    case "brand":
                        $result = $this->getData(
                            'https://shop.rivegauche.ru/rest/v1/rivegauche/products?pageSize=30&query=%3A%3AbrandCode%3A'
                            .$category->link.'&currentPage='.$i
                        );
                        break;
                }

                //print_r($result['products']);die;
                if (empty($result['products'])) {
                    $z = 0;
                } else {
                    $z = 1;
                    foreach ($result['products'] as $item) {
                        if (!empty($item['optionalPrices'][0])) {
                            $basePrice = $item['optionalPrices'][0];
                        }

                        if (!empty($item['optionalPrices'][1])) {
                            $bluePrice = $item['optionalPrices'][1];
                        }

                        if (!empty($item['optionalPrices'][2])) {
                            $goldPrice = $item['optionalPrices'][2];
                        }


                        if (!$item['hasVariantType']) {
                            $code = $item['code'];
                            $hasVariantType = 0;
                        } else {
                            $code = str_replace('base_', '', $item['code']);
                            $hasVariantType = 1;
                        }

                        $product = RivegaucheProduct::findOne(['article' => $code]);

                        if (!$product) {
                            $product = new RivegaucheProduct();
                            $product->article = $code;
                        }

                        if (!empty($basePrice)) {
                            $product->price = $basePrice[1]['value'];
                        } else {
                            $hasVariantType = 1;
                        }

                        if (!empty($bluePrice)) {
                            $product->blue_price = $bluePrice[1]['value'];
                        }
                        if (!empty($goldPrice)) {
                            $product->gold_price = $goldPrice[1]['value'];
                        }

                        $product->has_variant_type = $hasVariantType;
                        $product->link = 'https://shop.rivegauche.ru/newstore'.$item['url'];

                        if (!empty($product->category)) {
                            $product->category = $this->checkCategory($product->category, $category->category);
                        } else {
                            $product->category = $category->category;
                        }

                        if (!empty($product->group)) {
                            $product->group = $this->checkCategory($product->group, $category->group);
                        } else {
                            $product->group = $category->group;
                        }

                        if (!empty($product->sub_category)) {
                            $product->sub_category = $this->checkCategory(
                                $product->sub_category,
                                $category->sub_category
                            );
                        } else {
                            $product->sub_category = $category->sub_category;
                        }

                        if (!empty($item['images'])) {
                            foreach ($item['images'] as $image) {
                                if ($image['format'] == 'product') {
                                    $product->image_link = 'https://shop.rivegauche.ru'.$image['url'];
                                }

                            }
                        }

                        if (!empty($item['discountPrice'])) {
                            $product->special_price = $item['discountPrice']['value'];
                        }

                        if (!empty($item['subtitle'])) {
                            $product->description = $item['subtitle'];
                        }

                        if (!empty($item['name'])) {
                            $product->title = $item['name'];
                        } else {
                            $product->has_variant_type = 1;
                        }

                        if (!empty($item['manufacturer'])) {
                            $product->brand = $item['manufacturer'];
                        }
                        if (!empty($item['showcases'])) {
                            foreach ($item['showcases'] as $showcase) {
                                $product->showcases_new = ($showcase == 'showcases_new') ? 1 : 0;
                                $product->showcases_compliment = ($showcase == 'showcases_compliment') ? 1 : 0;
                                $product->showcases_offer = ($showcase == 'showcases_offer') ? 1 : 0;
                                $product->showcases_exclusive = ($showcase == 'showcases_exclusive') ? 1 : 0;
                                $product->showcases_expertiza = ($showcase == 'showcases_expertiza') ? 1 : 0;
                                $product->showcases_bestsellers = ($showcase == 'showcases_bestsellers') ? 1 : 0;
                            }
                        }
                        $product->deleted_at = '0000-00-00 00:00:00';
                        $product->save();
                    }
                    $i++;
                }

            } while ($z > 0);
        }
        echo "парсим страницы";
        $this->parsePages();
        $parsingStatus->end('rivegauche');
        PodruzkaProduct::updatePriceDiff();
        (new RivegaucheProduct())->setDeleted();

        return 0;
    }

    /**
     * @param $baseCategory
     * @param $oldCategory
     *
     * @return string
     */
    private function checkCategory($baseCategory, $oldCategory)
    {
        if ($baseCategory == 'Без категории') {
            $category = $oldCategory;
        } else {
            if ($oldCategory == 'Без категории') {
                $category = $baseCategory;
            } else {
                $category = $oldCategory;
            }
        }

        return $category;
    }

    /**
     * @param string $url
     *
     * @return mixed
     */
    private function getData($url)
    {
        $request = new \GuzzleHttp\Client();
        $result = $request->get($url, []);

        return $result->json();
    }

    /**
     * @param string $url
     *
     * @return null|\Symfony\Component\DomCrawler\Crawler
     */
    private function getDataPage($url)
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

    private function parsePages()
    {
        $riveLinks = RivegaucheProduct::findAll(['has_variant_type' => 1]);

        foreach ($riveLinks as $riveLink) {
            //$riveLink->link = 'https://shop.rivegauche.ru/newstore/ru/%D0%91%D1%80%D0%B5%D0%BD%D0%B4%D1%8B/%D0%92%D1%81%D0%B5-%D0%91%D1%80%D0%B5%D0%BD%D0%B4%D1%8B/GARNIER/Garnier-%D0%A0%D0%BE%D0%BB%D0%B8%D0%BA-%D0%B2%D0%BE%D0%BA%D1%80%D1%83%D0%B3-%D0%B3%D0%BB%D0%B0%D0%B7-%D0%92%D0%92-%D0%BA%D1%80%D0%B5%D0%BC-%D0%A1%D0%B5%D0%BA%D1%80%D0%B5%D1%82-%D1%81%D0%BE%D0%B2%D0%B5%D1%80%D1%88%D0%B5%D0%BD%D1%81%D1%82%D0%B2%D0%B0/p/769133';
            $crawler = $this->getDataPage($riveLink->link);
            if (!$crawler) {
                continue;
            }

            $attributes = [
                'link' => $riveLink->link,
                'group' => $riveLink->group,
                'category' => $riveLink->category,
                'sub_category' => $riveLink->sub_category,
            ];

            $service = new ParserService();
            $result = $service->productParse($crawler, ParserService::RIV, $attributes);
            //print_r($result);die;
            if (!empty($result->getUrls())) {
                foreach ($result->getUrls() as $url) {
                    $crawlerSub = $this->getDataPage($url);
                    if (!$crawlerSub) {
                        continue;
                    }

                    $attributes = [
                        'link' => $url,
                        'group' => $riveLink->group,
                        'category' => $riveLink->category,
                        'sub_category' => $riveLink->sub_category,
                    ];

                    $serviceSub = new ParserService();
                    $subResult = $serviceSub->productParse($crawlerSub, ParserService::RIV, $attributes);
                    $this->saveProduct($subResult);
                }
            } else {
                $this->saveProduct($result);
            }
        }
    }

    /**
     * @param Response $result
     */
    private function saveProduct(Response $result)
    {
        $product = RivegaucheProduct::findOne(['article' => $result->getArticle()]);

        if (!$product) {
            $product = new RivegaucheProduct();
        }
        $product->attributes = $result->toArray();
        $product->deleted_at = '0000-00-00 00:00:00';
        $product->special_price = $result->getNewPrice();

        try {
            $product->save();
        } catch (\Exception $e) {
            \Yii::error(
                sprintf(
                    'Exception %s сохранения артикула R %s data: %s',
                    $e->getMessage(),
                    json_encode($result->toArray())
                ),
                'cron'
            );
        }
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
