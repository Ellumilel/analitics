<?php

namespace app\commands;


use app\commands\entity\LetualCategory;
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

        foreach ($entity->getLinks() as $link) {
            $client = new Client();
            $crawler = $client->request('GET', $link);

            $urls = $crawler->filter('div.productItemDescription h3.title a')->each(function ($node) {
                $href = $node->attr('href');
                $url = $this->url.substr($href, 0, strpos($href, ";"));

                return $url;
            });
            foreach ($urls as $key => $url) {
                $linkModel = LetualLink::findOne(['link'=>$url]);
                if (!$linkModel) {
                    $linkModel = new LetualLink();
                }
                $linkModel->link = $url;

                $linkModel->group = $entity->getGroup($link);
                $linkModel->category = $entity->getCategory($link);
                $linkModel->sub_category = $entity->getSubCategory($link);
                $linkModel->save();
            }
        }

        return 0;
    }
}