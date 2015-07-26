<?php

namespace app\commands;

use app\models\IledebeauteLink;
use app\models\IledebeautePrice;
use app\models\IledebeauteProduct;
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
class IledebeauteProductController extends Controller
{
    /** @var string */
    private $url = 'http://iledebeaute.ru';

    /**
     * @return int
     */
    public function actionIndex()
    {
        /** @var $entity IledebeauteLink*/
        $entity = new IledebeauteLink();
        $offset = 0;

        do {
            $links = $entity->getLinks($offset, 20);

            if (!empty($links)) {
                foreach ($links as $link) {
                    $client = new Client();
                    $crawler = $client->request('GET', $link['link']);

                    //TODO Обработка
                    $head = $crawler->filter('div.b-product__bigColumn p.title')->each(function ($node) {
                        $brand = $node->filter('a.title__brand')->each(function ($subNode) {
                            return $subNode->text();
                        });

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
                    });

                    if(empty(reset($head))) {
                        $head = $crawler->filter('div.b-product__item__promo__content')->each(function ($node) {
                            $title = $node->filter('div.b-product__item__promo__title h1')->each(function ($subNode) {
                                return $subNode->text();
                            });

                            $brand = $node->filter('div.b-product__item__promo__logo a')->each(function ($subNode) {
                                return $subNode->attr('href');
                            });

                            $title = reset($title);
                            $title = str_replace('\r', '', $title);
                            $title = str_replace('\n', '', $title);
                            $title = trim($title);
                            $title = str_replace(' ', '', $title);
                            $title = str_replace('</span>', '', $title);
                            $title = str_replace('<span>', '', $title);

                            return [
                                'brand' => reset($brand),
                                'title' => $title,
                            ];
                        });
                    }

                    $body = $crawler->filter('table.b-showcase')->each(function ($node) {
                        $items = $node->filter('tr.b-showcase__item')->each(function ($sub) {
                            $price = $sub->filter('td.b-showcase__item__price')->each(function ($sub) {
                                $oldPrice = $sub->filter('span.old')->each(function ($sub) {
                                    return $sub->text();
                                });
                                $newPrice = $sub->filter('span.new')->each(function ($sub) {
                                    return $sub->text();
                                });

                                if(empty(reset($oldPrice)) && empty(reset($newPrice))) {
                                    $newPrice = $sub->filter('span.def')->each(function ($sub) {
                                        return $sub->text();
                                    });
                                }

                                $newPrice = trim(reset($newPrice));
                                $newPrice = str_replace(' ', '', $newPrice);
                                $newPrice = str_replace('*', '', $newPrice);
                                $newPrice = str_replace('\r', '', $newPrice);
                                $newPrice = str_replace('\n', '', $newPrice);

                                $oldPrice = trim(reset($oldPrice));
                                $oldPrice = str_replace(' ', '', $oldPrice);
                                return [
                                    'oldPrice' => (empty($oldPrice)) ? $newPrice : $oldPrice,
                                    'newPrice' => $newPrice,
                                ];
                            });

                            $article = $sub->filter('td.b-showcase__item__descr p.b-showcase__item__brand')->each(function ($sub) {
                                return $sub->text();
                            });
                            $image = $sub->filter('td.b-showcase__item__img img')->each(function ($sub) {
                                return 'http::'.$sub->attr('src');
                            });
                            $description = $sub->filter('td.b-showcase__item__descr p.b-showcase__item__link')->each(function ($sub) {
                                return $sub->text();
                            });

                            if(!empty(reset($description))) {
                                $description = trim(reset($description));
                                $description = str_replace(' ', '', $description);
                                $description = str_replace('*', '', $description);
                                $description = str_replace('\r', '', $description);
                                $description = str_replace('\n', '', $description);
                                $description = trim($description);
                            }

                            if(empty(reset($article)) && empty(reset($price))) {
                                return [];
                            }

                            return [
                                'price' => reset($price),
                                'article' => reset($article),
                                'image' => reset($image),
                                'description' => $description,
                            ];
                        });
                        return $items;
                    });

                    if(empty(reset($body))) {
                        $body = $crawler->filter('tr.b-cart__showcase__item')->each(function ($sub) {
                            $price = $sub->filter('td.b-cart__showcase__item__price')->each(function ($sub) {
                                $oldPrice = $sub->filter('span.old')->each(function ($sub) {
                                    return $sub->text();
                                });

                                $newPrice = $sub->filter('span.new')->each(function ($sub) {
                                    return $sub->text();
                                });

                                if (empty(reset($oldPrice)) && empty(reset($newPrice))) {
                                    $newPrice = $sub->filter('span.def')->each(function ($sub) {
                                        return $sub->text();
                                    });
                                }

                                $newPrice = trim(reset($newPrice));
                                $newPrice = str_replace(' ', '', $newPrice);
                                $newPrice = str_replace('*', '', $newPrice);
                                $newPrice = str_replace('\r', '', $newPrice);
                                $newPrice = str_replace('\n', '', $newPrice);

                                $oldPrice = trim(reset($oldPrice));
                                $oldPrice = str_replace(' ', '', $oldPrice);

                                return [
                                    'oldPrice' => (empty($oldPrice)) ? $newPrice : $oldPrice,
                                    'newPrice' => $newPrice,
                                ];
                            });

                            $article = $sub->filter('td p.b-cart__showcase__item__brand')->each(function (
                                $sub
                            ) {
                                return $sub->text();
                            });
                            $image = $sub->filter('td.b-cart__showcase__item__img img')->each(function ($sub) {
                                return $this->url.$sub->attr('src');
                            });
                            $description = $sub->filter('td.b-showcase__item__descr p.b-showcase__item__link')->each(function (
                                $sub
                            ) {
                                return $sub->text();
                            });
                            $description = trim(reset($description));
                            $description = str_replace(' ', '', $description);
                            $description = str_replace('*', '', $description);
                            $description = str_replace('\r', '', $description);
                            $description = str_replace('\n', '', $description);
                            $description = trim($description);

                            return [
                                'price' => reset($price),
                                'article' => reset($article),
                                'image' => reset($image),
                                'description' => $description,
                            ];
                        });
                    }

                    $result = [
                        'head' => reset($head),
                        'body' => reset($body),
                    ];

                    if(!empty($result)) {
                        foreach ($result['body'] as $item) {
                            if(!empty($item['article'])) {
                                $product = IledebeauteProduct::findOne(['article' => $item['article']]);
                                if (!$product) {
                                    $product = new IledebeauteProduct();
                                }

                                $product->brand = $result['head']['brand'];
                                $product->title = $result['head']['title'];
                                $product->article = $item['article'];
                                $product->category = $link['category'];
                                $product->group = $link['group'];
                                $product->link = $link['link'];
                                $product->sub_category = $link['sub_category'];
                                $product->image_link = $item['image'];
                                $product->description = $item['description'];

                                $iledebeautePrice = new IledebeautePrice();
                                $iledebeautePrice->article = $item['article'];

                                if (!empty($item['price']['newPrice'])) {
                                    $iledebeautePrice->new_price = $item['price']['newPrice'];
                                }
                                if (!empty($item['price']['oldPrice'])) {
                                    $iledebeautePrice->old_price = $item['price']['oldPrice'];
                                }
                                $product->save();
                                if (!empty($iledebeautePrice)) {
                                    $iledebeautePrice->save();
                                }
                            }
                        }
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
                        $product->brand = $result['brand'];
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
}
