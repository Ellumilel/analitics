<?php

namespace app\commands;

use yii\console\Controller;
use yii\sphinx\Query;
use yii\db\Expression;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 *
 * Class SphinxController
 *
 * @package app\commands
 */
class SphinxController extends Controller
{
    /** @var string */

    /**
     * @return int
     */
    public function actionIndex()
    {
        $query = new Query;
        $rows = $query->from('iproduct')
            ->match(new Expression(':match', ['match' => '@(description) ' . \Yii::$app->sphinx->escapeMatchValue("киви")]))
            ->all();
        $rows = $query->from('iproduct')
            ->match(new Expression(':match', ['match' => '@(description) ' . \Yii::$app->sphinx->escapeMatchValue("LADY REBEL ROCK DELUXE")]))
            ->all();
        print_r($rows);die;
        return $rows;
    }
}
