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
        /** @var $entity IledebeauteCategory */
        $entity = new IledebeauteCategory();
        $offset = 0; // начало отсчета

        do {
            $links = $entity->getLinks($offset, 5);
            if (!empty($links)) {
                foreach ($links as $link) {
                    $page = 1;
                    $client = new Client();

                    do {
                        $crawler = $client->request('GET', $link['link'].sprintf('page%d/?perpage=36', $page));
                        // print_r($link['link'].sprintf('page%d/?perpage=36', $page));die;
                        $urls = $crawler->filter('div.b-showcase__item a')->each(function ($node) {
                            $href = $node->attr('href');
                            $url = $this->url.$href;

                            return $url;
                        });

                        $i = (empty($urls)) ? 0 : 1; // выход из цикла

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
                    } while ($i > 0);
                }
                $z = 1;
                $offset += 5;
                unset($links);
                unset($client);
            } else {
                $z = 0;
            }
        } while ($z > 0);

        return 0;
    }
}
