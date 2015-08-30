<?php

namespace app\commands;

use app\models\PodruzkaProduct;
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
        $entity = new PodruzkaProduct();
        $offset = 0;

        do {
            $products = $entity->getEntity($offset, 20);
            if (!empty($products)) {
                foreach ($products as $product) {
                    if (empty($product->ile_id)) {
                        $query = new Query;
                        $str = '';
                        $rows = $query->from('iproduct')
                            ->match(new Expression(':match', ['match' => '@(description) ' . \Yii::$app->sphinx->escapeMatchValue($product->detail)]))
                            ->all();
                        foreach ($rows as $row) {
                            if (!empty($row['id'])) {
                                // проставляем идентификатор
                                $str .= $row['id'].';';
                            }
                        }

                        // обновляем таблицу podruzka_product
                        if (!empty($str)) {
                            $productEntity = PodruzkaProduct::findOne(['id' => $product['id']]);
                            if ($productEntity instanceof PodruzkaProduct) {
                                $productEntity->ile_id = $str;
                                $productEntity->save();
                            }
                        }
                    }
                }

                $z = 1;
                $offset += 20;
                unset($productEntity);
                unset($query);
            } else {
                $z = 0;
            }
        } while ($z > 0);

        return 0;
    }
}
