<?php

namespace app\commands;

use app\models\ElizeCategory;
use app\models\ElizeLink;
use app\models\IledebeauteCategory;
use app\models\IledebeauteLink;
use app\models\LetualCategory;
use app\models\LetualLink;
use app\models\RivegaucheCategory;
use app\models\RivegaucheLink;
use app\src\Parser\ParserService;
use app\src\Parser\Request\Link\Elize;
use yii\console\Controller;
use yii\db\ActiveRecord;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 *
 * Class LinkController
 *
 * @package app\commands
 */
class LinkController extends Controller
{
    /**
     * Метод запускается по крон собирает ссылки по Летуаль
     */
    public function actionLetual()
    {
        $entity = new LetualCategory();
        $this->parseLinkData($entity);
    }

    /**
     * Метод запускается по крон собирает ссылки по Элизе
     */
    public function actionElize()
    {
        $entity = new ElizeCategory();
        $this->parseLinkData($entity);
    }

    /**
     * Метод запускается по крон собирает ссылки по РивГош
     */
    public function actionRivegauche()
    {
        $entity = new RivegaucheCategory();
        $this->parseLinkData($entity);
    }

    /**
     * Метод запускается по крон собирает ссылки по ИлДэБотте
     */
    public function actionIledebeaute()
    {
        $entity = new IledebeauteCategory();
        $this->parseLinkData($entity);
    }

    /**
     * @param ActiveRecord $category
     *
     * @return int
     */
    private function parseLinkData(ActiveRecord $category)
    {
        $offset = 0; // начало отсчета
        if ($category instanceof LetualCategory) {
            $linkEntity = new LetualLink();
        } elseif ($category instanceof RivegaucheCategory) {
            $linkEntity = new RivegaucheLink();
        } elseif ($category instanceof IledebeauteCategory) {
            $linkEntity = new IledebeauteLink();
        } elseif ($category instanceof ElizeCategory) {
            $linkEntity = new ElizeLink();
        } else {
            return 0;
        }

        do {
            $links = $category->getLinks($offset, 1);
            if (!empty($links)) {
                foreach ($links as $link) {
                    $service = new ParserService();
                    if ($linkEntity instanceof LetualLink) {
                        $urls = $service->collectLLinkData($link->link);
                        //print_r($urls);die;
                    } elseif ($linkEntity instanceof RivegaucheLink) {
                        $urls = $service->collectRLinkData($link->link);
                    } elseif ($linkEntity instanceof IledebeauteLink) {
                        $urls = $service->collectILinkData($link->link);
                    } elseif ($linkEntity instanceof ElizeLink) {
                        $urls = $service->collectELinkData($link->link);
                    } else {
                        $urls = [];
                    }

                    foreach ($urls as $key => $url) {
                        $attributes = [
                            'group' => $link->group,
                            'category' => $link->category,
                            'sub_category' => $link->sub_category,
                        ];
                        $linkModel = $linkEntity::getByLink($url);
                        $linkModel->setAttributes($attributes);
                        $linkModel->save();
                    }
                }
                $z = 1;
                $offset += 1;
                unset($links);
                unset($client);
            } else {
                $z = 0;
            }
        } while ($z > 0);

        return 0;
    }
}
