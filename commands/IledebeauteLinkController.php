<?php

namespace app\commands;


use app\models\IledebeauteCategory;
use app\models\IledebeauteLink;
use yii\console\Controller;
use Goutte\Client;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 *
 * Class IledebeauteLinkController
 *
 * @package app\commands
 */
class IledebeauteLinkController extends Controller
{
    /** @var string */
    private $url = 'http://iledebeaute.ru';

    /**
     * @return int
     */
    public function actionIndex()
    {
        /** @var $entity IledebeauteCategory*/
        $entity = new IledebeauteCategory();

        foreach ($entity->getLinks() as $link) {
            $page = 1;
            $i = 1;

            $client = new Client();

            do {
                $crawler = $client->request('GET', $link['link'].sprintf('page%d/?perpage=36', $page));
               // print_r($link['link'].sprintf('page%d/?perpage=36', $page));die;
                $urls = $crawler->filter('div.b-showcase__item a')->each(function ($node) {
                    $href = $node->attr('href');
                    $url = $this->url.$href;

                    return $url;
                });

                if(empty($urls)) {$i = 0;}

                foreach ($urls as $key => $url) {
                    $linkModel = IledebeauteLink::findOne(['link' => $url]);
                    if (!$linkModel) {
                        $linkModel = new IledebeauteLink();
                    }
                    $linkModel->link = $url;

                    $linkModel->group = $link['group'];
                    $linkModel->category = $link['category'];
                    $linkModel->sub_category = $link['sub_category'];
                    $linkModel->save();
                }

                $page++;
            } while($i > 0);
        }

        return 0;
    }
}