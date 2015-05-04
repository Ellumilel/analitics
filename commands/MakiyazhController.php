<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;


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
        $client = new Client();
        $crawler = $client->request('GET', 'http://www.letu.ru/makiyazh?q_docSortOrder=descending&viewAll=true');

        $crawler->filter('div.productItemDescription h3.title a')->each(function ($node) {
            $links = new ProductLink();
            $href = $node->attr('href');
            $r = preg_split('/.+\//i', $href);
            $ids = str_replace('?navAction=push','',$r[1]);
            $ids = preg_replace('/;.+/i', '', $ids);

            $urlPos = strpos($href, "?");
            $url = substr($href, 0, $urlPos);
            $url = substr($url, 0, strpos($href, ";"));

            $links->product_id = $ids;
            $links->link = $url;
            $links->validate();
            $links->save();
        });

        return 0;
    }
}