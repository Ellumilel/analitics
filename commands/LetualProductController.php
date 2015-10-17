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

                        $image = $node->filter('td img')->each(function ($node) {
                            return $this->url.$node->attr('src');
                        });

                        $article = trim(reset($article));
                        $article = str_replace('Артикул ', '', $article);
                        $article = preg_replace("/[^a-zA-Z0-9]/", "", $article);

                        return [
                            'title' => reset($title),
                            'article' => $article,
                            'description' => reset($description),
                            'price' => reset($price),
                            'image' => reset($image),
                        ];
                    });

                    $brand = $crawler->filter('#brandImage')->each(function ($node) {
                        return $node->attr('alt');
                    });

                    //Если не нашли картинку в списке выбора
                    if (empty($result['image'])) {
                        $image = $crawler->filter('div.atg_store_productImage img')->each(function ($node) {
                            return $this->url.$node->attr('src');
                        });

                        $result['image'] = reset($image);
                    }

                    $result = $this->checkArray($result);

                    $result['brand'] = reset($brand);
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
                    $letualPrice->new_price = $this->getPrice($data['price']['newPrice']);
                    $letualPrice->old_price = $this->getPrice($data['price']['oldPrice']);

                    if (!empty($product)) {
                        $product->brand = $this->clearBrand($result['brand']);
                        $product->image_link = $result['image'];
                        $product->link = $result['link'];
                        $product->group = $result['group'];
                        $product->category = $result['category'];
                        $product->sub_category = $result['sub_category'];

                        $product->new_price = $letualPrice->new_price;
                        $product->old_price = $letualPrice->old_price;

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
     * и наименование брендов
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
            case "L`OREAL PARIS":
                $brand = 'LOREAL';
                break;
            case "YES TO...":
                $brand = 'YES TO';
                break;
            case "DSQUARED2":
                $brand = 'DSQUARED';
                break;
            case "COLOR MASK":
                $brand = 'SCHWARZKOPF';
                break;
            case "GLISS KUR":
                $brand = 'SCHWARZKOPF';
                break;
            case "GOT2B":
                $brand = 'SCHWARZKOPF';
                break;
            case "MILLION COLOR":
                $brand = 'SCHWARZKOPF';
                break;
            case "PALETTE":
                $brand = 'SCHWARZKOPF';
                break;
            case "PERFECT MOUSSE":
                $brand = 'SCHWARZKOPF';
                break;
            case "TAFT":
                $brand = 'SCHWARZKOPF';
                break;
            case "TSUBAKI":
                $brand = 'SHISEIDO';
                break;
        }

        $brand = strtoupper($brand);
        return $brand;
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
