<?php

namespace app\commands;

use app\models\IledebeauteLink;
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

                    //print_r($crawler);die;
                    $urls = $crawler->filter('div.productItemDescription h3.title a')->each(function ($node) {
                        $href = $node->attr('href');
                        $url = $this->url . substr($href, 0, strpos($href, ";"));

                        return $url;
                    });

                    foreach ($urls as $key => $url) {
                        $linkModel = LetualLink::findOne(['link' => $url]);
                        if (!$linkModel) {
                            $linkModel = new LetualLink();
                        }
                        $linkModel->link = $url;

                        $linkModel->group = $link['group'];
                        $linkModel->category = $link['category'];
                        $linkModel->sub_category = $link['sub_category'];
                        $linkModel->save();
                    }
                }

                $z = 1;
                $offset += 5;
                unset($links);
                unset($linkModel);
                unset($urls);
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
