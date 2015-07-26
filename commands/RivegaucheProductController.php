<?php
namespace app\commands;

use app\models\IledebeauteLink;
use app\models\RivegaucheLink;
use yii\console\Controller;
use Goutte\Client;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 *
 * Class RivegaucheProductController
 *
 * @package app\commands
 */
class RivegaucheProductController extends Controller
{
    /** @var string */
    private $url = 'http://shop.rivegauche.ru/store/';

    /**
     * @return int
     */
    public function actionIndex()
    {
        /** @var $entity RivegaucheLink*/
        $entity = new RivegaucheLink();
        $offset = 0;
        do {
            $links = $entity->getLinks($offset, 3);
            if (!empty($links)) {
                foreach ($links as $link) {
                    $client = new Client();
                    $crawler = $client->request('GET', $link['link']);

                    $head = $crawler->filter('div.es_product')->each(function ($node) {
                        $title = $node->filter('div.es_right_full_name h1')->each(function ($subNode) {
                            return $subNode->text();
                        });

                        $brand = $node->filter('div.es_right_lable img')->each(function ($subNode) {
                            return $subNode->attr('alt');
                        });

                        $description = $node->filter('div.es_right_price_type')->each(function ($subNode) {
                            return $subNode->text();
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

                            return [
                                'gold_price' => reset($goldPrice),
                                'blue_price' => reset($bluePrice),
                                'price' => (!empty(reset($price))) ? reset($price) : reset($fixPrice),
                            ];
                        });

                        $imageLink = $node->filter('div#primary_image img')->each(function ($subNode) {
                            return $subNode->attr('src');
                        });
                        $showcasesOffer = $node->filter('div.showcases_offer')->each(function ($subNode) {
                            return $subNode;
                        });
                        return [
                            'title' => reset($title),
                            'brand' => reset($brand),
                            'price' => reset($price),
                            'description' => reset($description),
                            'image_link' => reset($imageLink),
                            'showcases_offer' => !empty($showcasesOffer) ? true : false,
                        ];
                    });
                    $head = reset($head);
                    $head['link'] = $link['link'];
                    print_r($head);die;
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
}
