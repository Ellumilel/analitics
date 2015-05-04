<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;


use app\models\Price;
use app\models\ProductLink;
use yii\console\Controller;
use Goutte\Client;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 *
 * Class MakiyazhController
 *
 * @package app\commands
 */
class MakiyazhController extends Controller
{
    public function actionIndex()
    {
        $products = ProductLink::find()->all();
        foreach($products as $product) {
            $client = new Client();
            $crawler = $client->request('GET', 'http://www.letu.ru'.$product->link);

            $new_price = $crawler->filter('td.price p.new_price')->each(function ($node) {
                return $node->text();
            });
            $old_price = $crawler->filter('td.price p.old_price')->each(function ($node) {
                return $node->text();
            });
            $article = $crawler->filter('table.atg_store_productSummary td.item p.article')->each(function ($node) {
                return $node->text();
            });
            $description = $crawler->filter('table.atg_store_productSummary td.item p.description')->each(function ($node) {
                return $node->text();
            });
            $title = $crawler->filter('table.atg_store_productSummary td.item h2')->each(function ($node) {
                return $node->text();
            });
            $brand = $crawler->filter('div#brandInfo img')->each(function ($node) {
                return $node->attr('alt');
            });
            foreach($article as $key => $one) {
                $price = new Price();

                $price->article = trim($one);
                $price->article = str_replace('Артикул ', '', $price->article);
                $price->article = preg_replace ("/[^a-zA-Z0-9]/","",$price->article);

                $price->description = trim($description[$key]);

                $price->title = trim($title[$key]);
                $price->brand = trim(reset($brand));

                $price->new_price = trim($new_price[$key]);
                $price->new_price = str_replace(' ', '', $price->new_price);
                $price->new_price = str_replace('*', '', $price->new_price);
                $price->new_price = str_replace('\r', '', $price->new_price);
                $price->new_price = str_replace('\n', '', $price->new_price);
                $price->old_price = trim($old_price[$key]);
                $price->old_price = str_replace(' ', '', $price->old_price);
                $price->link = 'http://www.letu.ru'.$product->link;
                $price->save();
            }
            //$price->new_price = $new_price;
        }
        return 0;
    }
}