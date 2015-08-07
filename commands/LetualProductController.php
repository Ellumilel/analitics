<?php

namespace app\commands;

use app\models\LetualLink;
use app\models\LetualPrice;
use app\models\LetualProduct;
use yii\console\Controller;
use Goutte\Client;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 *
 * Class GetArticle
 *
 * @package app\commands
 */
class LetualProductController extends Controller
{
    /** @var string */
    private $url = 'http://www.letu.ru';

    /**
     * @return int
     */
    public function actionIndex()
    {
        $entity = new LetualLink();
        $offset = 0;
        do {
            $links = $entity->getLinks($offset, 20);
            if (!empty($links)) {
                foreach ($links as $link) {
                    $client = new Client();
                    $crawler = $client->request('GET', $link->link);

                    $result = $crawler->filter('table.atg_store_productSummary tr')->each(function ($node) {
                        $title = $node->filter('td.item h2')->each(function ($subNode) {
                            return $subNode->text();
                        });

                        $article = $node->filter('td.item p.article')->each(function ($subNode) {
                            return $subNode->text();
                        });

                        $description = $node->filter('td.item p.description')->each(function ($subNode) {
                            return $subNode->text();
                        });

                        $price = $node->filter('td.price')->each(function ($subNode) {
                            $oldPrice = $subNode->filter('p.old_price')->each(function ($subsNode) {
                                return $subsNode->text();
                            });
                            $newPrice = $subNode->filter('p.new_price')->each(function ($subsNode) {
                                return $subsNode->text();
                            });

                            $newPrice = trim(reset($newPrice));
                            $newPrice = str_replace(' ', '', $newPrice);
                            $newPrice = str_replace('*', '', $newPrice);
                            $newPrice = str_replace('\r', '', $newPrice);
                            $newPrice = str_replace('\n', '', $newPrice);

                            $oldPrice = trim(reset($oldPrice));
                            $oldPrice = str_replace(' ', '', $oldPrice);

                            return [
                                'oldPrice' => $oldPrice,
                                'newPrice' => $newPrice,
                            ];
                        });

                        $article = trim(reset($article));
                        $article = str_replace('Артикул ', '', $article);
                        $article = preg_replace("/[^a-zA-Z0-9]/", "", $article);

                        return [
                            'title' => reset($title),
                            'article' => $article,
                            'description' => reset($description),
                            'price' => reset($price),
                        ];
                    });

                    $brand = $crawler->filter('#brandImage')->each(function ($node) {
                        return $node->attr('alt');
                    });

                    $image = $crawler->filter('div.atg_store_productImage img')->each(function ($node) {
                        return $this->url.$node->attr('src');
                    });

                    $result = $this->checkArray($result);

                    $result['brand'] = reset($brand);
                    $result['image'] = reset($image);
                    $result['link'] = $link->link;
                    $result['group'] = $link->group;
                    $result['category'] = $link->category;
                    $result['sub_category'] = $link->sub_category;

                    $this->saveResult($result);
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
     * Очищает массив от пустых элементов
     *
     * @param array $result
     *
     * @return array
     */
    private function checkArray($result)
    {
        $data = [];
        foreach ($result as $key=>$res) {
            if (empty($res['article'])) {
                unset($result[$key]);
            } else {
                $data['elements'][] = $result[$key];
            }
        }

        return $data;
    }

    private function saveResult($result)
    {
        foreach($result as $key=>$res) {
            if($key == 'elements') {
                foreach ($res as $data) {
                    $product = LetualProduct::findOne(['article' => $data['article']]);

                    if (!$product) {
                        $product = new LetualProduct();
                    }

                    $product->article = $data['article'];
                    $product->title = $data['title'];
                    $product->description = $data['description'];

                    $letualPrice = new LetualPrice();
                    $letualPrice->article = $data['article'];

                    if(!empty($data['price']['newPrice'])) {
                        $letualPrice->new_price = $data['price']['newPrice'];
                    }
                    if(!empty($data['price']['oldPrice'])) {
                        $letualPrice->old_price =$data['price']['oldPrice'];
                    }

                    if (!empty($product)) {
                        $product->brand = $this->clearBrand($result['brand']);
                        $product->image_link = $result['image'];
                        $product->link = $result['link'];
                        $product->group = $result['group'];
                        $product->category = $result['category'];
                        $product->sub_category = $result['sub_category'];

                        if(!empty($letualPrice->new_price)) {
                            $product->new_price = $letualPrice->new_price;
                        }

                        if(!empty($letualPrice->old_price)) {
                            $product->old_price = $letualPrice->old_price;
                        }

                        $product->save();
                        if (!empty($letualPrice)) {
                            $letualPrice->save();
                        }
                    }
                }
            }
        }
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
            case 'DOLCE&GABBANA':
                $brand = 'DOLCE & GABBANA';
                break;
            case 'DOLCE & GABBANA MAKE UP':
                $brand = 'DOLCE & GABBANA';
                break;
            case 'Dolce&Gabbana':
                $brand = 'DOLCE & GABBANA';
                break;
        }

        $brand = strtoupper($brand);
        return $brand;
    }
}
