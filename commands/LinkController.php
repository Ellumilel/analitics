<?php

namespace app\commands;

use app\models\IledebeauteCategory;
use app\models\IledebeauteLink;
use app\models\LetualCategory;
use app\models\LetualLink;
use app\models\RivegaucheCategory;
use app\models\RivegaucheLink;
use app\src\Parser\ParserService;
use yii\console\Controller;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 *
 * Class LinkController
 *
 * @package app\commands
 */
class LinkController extends Controller
{
    public function actionLetual()
    {
        $entity = new LetualCategory();
        $offset = 0;
        $urls = [];

        do {
            $links = $entity->getLinks($offset, 5);
            if (!empty($links)) {
                /** @var LetualCategory $link */
                foreach ($links as $link) {
                    if (!empty($link->link)) {
                        $service = new ParserService();
                        $urls = $service->collectLLinkData($link['link']);
                    }
                    foreach ($urls as $key => $url) {
                        $attributes = [
                            'group' => $link->group,
                            'category' => $link->category,
                            'sub_category' => $link->sub_category,
                        ];
                        $linkModel = LetualLink::getByLink($url);
                        $linkModel->setAttributes($attributes);
                        $linkModel->save();
                    }
                }

                $z = 1;
                $offset += 5;
                unset($links);
                unset($linkModel);
                unset($urls);
            } else {
                $z = 0;
            }
        } while ($z > 0);
        return 0;
    }

    public function actionRivegauche()
    {
        $entity = new RivegaucheCategory();
        $offset = 0;

        do {
            $links = $entity->getLinks($offset, 5);
            if (!empty($links)) {
                /** @var RivegaucheCategory $link */
                foreach ($links as $link) {
                    $service = new ParserService();
                    $urls = $service->collectRLinkData($link->link);

                    foreach ($urls as $key => $url) {
                        $attributes = [
                            'group' => $link->group,
                            'category' => $link->category,
                            'sub_category' => $link->sub_category,
                        ];
                        $linkModel = RivegaucheLink::getByLink($url);
                        $linkModel->setAttributes($attributes);
                        $linkModel->save();
                    }
                }
                $z = 1;
                $offset += 5;
                unset($links);
                unset($links);
                unset($client);
            } else {
                $z = 0;
            }
        } while ($z > 0);

        return 0;
    }

    /**
     * @return int
     */
    public function actionIledebeaute()
    {
        $entity = new IledebeauteCategory();
        $offset = 0; // начало отсчета

        do {
            $links = $entity->getLinks($offset, 5);
            if (!empty($links)) {
                /** @var IledebeauteCategory $link */
                foreach ($links as $link) {
                    $service = new ParserService();
                    $urls = $service->collectILinkData($link->link);
                    foreach ($urls as $key => $url) {
                        $attributes = [
                            'group' => $link->group,
                            'category' => $link->category,
                            'sub_category' => $link->sub_category,
                        ];
                        $linkModel = IledebeauteLink::getByLink($url);
                        $linkModel->setAttributes($attributes);
                        $linkModel->save();
                    }
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
