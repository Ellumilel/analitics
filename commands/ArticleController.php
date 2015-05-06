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
 * Class GetArticle
 *
 * @package app\commands
 */
class ArticleController extends Controller
{
    private $links = [
        'http://www.letu.ru/makiyazh/dlya-litsa/osnova-dlya-makiyazha?q_docSortOrder=descending&viewAll=true',
        'http://www.letu.ru/makiyazh/dlya-litsa/tonalnye-sredstva?q_docSortOrder=descending&viewAll=true',
        'http://www.letu.ru/makiyazh/dlya-litsa/korrektiruyushchie-sredstva?q_docSortOrder=descending&viewAll=true',
        'http://www.letu.ru/makiyazh/dlya-litsa/pudra?q_docSortOrder=descending&viewAll=true',
        'http://www.letu.ru/makiyazh/dlya-litsa/rumyana?q_docSortOrder=descending&viewAll=true',
        'http://www.letu.ru/makiyazh/dlya-litsa/matiruyushchie-sredstva?q_docSortOrder=descending&viewAll=true',
        'http://www.letu.ru/makiyazh/dlya-glaz/tush?q_docSortOrder=descending&viewAll=true',
        'http://www.letu.ru/makiyazh/dlya-glaz/teni?q_docSortOrder=descending&viewAll=true',
        'http://www.letu.ru/makiyazh/dlya-glaz/konturnye-karandashi-i-podvodka?q_docSortOrder=descending&viewAll=true',
        'http://www.letu.ru/makiyazh/dlya-glaz/osnova-dlya-makiyazha?q_docSortOrder=descending&viewAll=true',
        'http://www.letu.ru/makiyazh/dlya-glaz/dlya-brovei?q_docSortOrder=descending&viewAll=true',
    ];
    public function actionIndex()
    {
        foreach ($this->links as $link) {
            $client = new Client();
            $crawler = $client->request('GET', $link);

            $crawler->filter('div.productItemDescription h3.title a')->each(function ($node) {
                $href = $node->attr('href');
                $r = preg_split('/.+\//i', $href);
                $ids = str_replace('?navAction=push','',$r[1]);
                $ids = preg_replace('/;.+/i', '', $ids);
            });
        }

        return 0;
    }
}