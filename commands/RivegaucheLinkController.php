<?php

namespace app\commands;


use app\models\RivegaucheCategory;
use app\models\RivegaucheLink;
use yii\console\Controller;
use Goutte\Client;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 *
 * Class RivegaucheLinkController
 *
 * @package app\commands
 */
class RivegaucheLinkController extends Controller
{
    /** @var string */
    private $url = 'http://shop.rivegauche.ru';

    /**
     * @return int
     */
    public function actionIndex()
    {
        /** @var $entity RivegaucheCategory*/
        $entity = new RivegaucheCategory();

        foreach ($entity->getLinks() as $link) {
            $page = 0;
            $client = new Client();
            $i = 1;
            do {
                $crawler = $client->request('GET', $link['link'].'&page='.$page);

                $urls = $crawler->filter('div.es_product_images a')->each(function ($node) {
                    $href = $node->attr('href');
                    $url = $this->url.$href;

                    return $url;
                });

                if(empty($urls)) {$i = 0;}

                foreach ($urls as $key => $url) {
                    $linkModel = RivegaucheLink::findOne(['link' => $url]);
                    if (!$linkModel) {
                        $linkModel = new RivegaucheLink();
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