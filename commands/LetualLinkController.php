<?php

namespace app\commands;


use app\models\LetualCategory;
use app\models\LetualLink;
use yii\console\Controller;
use Goutte\Client;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 *
 * Class GetArticle
 *
 * @package app\commands
 */
class LetualLinkController extends Controller
{
    /** @var string */
    private $url = 'http://www.letu.ru';

    /**
     * @return int
     */
    public function actionIndex()
    {
        /** @var $entity LetualCategory*/
        $entity = new LetualCategory();
        $offset = 0;

        do {
            $links = $entity->getLinks($offset, 5);
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
}
