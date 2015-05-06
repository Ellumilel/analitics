<?php

namespace app\commands;


use app\commands\entity\LetualCategory;
use app\models\LetualLink;
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
class LetualController extends Controller
{
    /** @var string */
    private $url = 'http://www.letu.ru';

    /**
     * @return int
     */
    public function actionIndex()
    {
        $entity = new LetualCategory();

        foreach ($entity->getLinks() as $link) {
            $client = new Client();
            $crawler = $client->request('GET', $link);

            $crawler->filter('div.productItemDescription h3.title a')->each(function ($node) {
                $href = $node->attr('href');
                $url = $this->url.substr($href, 0, strpos($href, ";"));

                $linkModel = new LetualLink();
                $linkModel->link = $url;
                $linkModel->validate();
                $linkModel->save();
            });
        }

        return 0;
    }
}