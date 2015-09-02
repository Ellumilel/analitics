<?php

namespace app\commands;

use app\models\IledebeauteProduct;
use app\models\LetualProduct;
use app\models\PodruzkaProduct;
use app\models\RivegaucheProduct;
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
                            ->match(new Expression(':match', ['match' => '@(description) ' . \Yii::$app->sphinx->escapeMatchValue($product->title)]))
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

    public function actionRpodr()
    {
        $entity = new RivegaucheProduct();
        $offset = 0;

        do {
            $products = $entity->getEntity($offset, 20);
            if (!empty($products)) {
                foreach ($products as $product) {
                    if (empty($product->rive_id)) {
                        $query = new Query;
                        $str = '';
                        $rows = $query->from('pproduct')
                            ->match(new Expression(':match', ['match' => '@(description) ' . \Yii::$app->sphinx->escapeMatchValue($product->title)]))
                            ->all();
                        foreach ($rows as $row) {
                            if (!empty($row['id'])) {
                                // проставляем идентификатор
                                $str = $row['id'];

                                break;
                            }
                        }

                        // обновляем таблицу podruzka_product
                        if (!empty($str)) {
                            $productEntity = PodruzkaProduct::findOne(['id' => $str]);

                            if ($productEntity instanceof PodruzkaProduct) {
                                $productEntity->rive_id = (string) $product->id;
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

    public function actionIpodr()
    {
        $entity = new IledebeauteProduct();
        $offset = 0;

        do {
            $products = $entity->getEntity($offset, 20);
            if (!empty($products)) {
                foreach ($products as $product) {
                    if (empty($product->ile_id)) {
                        $query = new Query;
                        $str = '';
                        $rows = $query->from('pproduct')
                            ->match(new Expression(':match', ['match' => '@(description) ' . \Yii::$app->sphinx->escapeMatchValue($product->title)]))
                            ->all();
                        foreach ($rows as $row) {
                            if (!empty($row['id'])) {
                                // проставляем идентификатор
                                $str = $row['id'];

                                break;
                            }
                        }

                        // обновляем таблицу podruzka_product
                        if (!empty($str)) {
                            $productEntity = PodruzkaProduct::findOne(['id' => $str]);

                            if ($productEntity instanceof PodruzkaProduct) {
                                $productEntity->ile_id = (string) $product->id;
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

    public function actionLpodr()
    {
        $entity = new LetualProduct();
        $offset = 0;

        do {
            $products = $entity->getEntity($offset, 20);
            if (!empty($products)) {
                foreach ($products as $product) {
                    if (empty($product->letu_id)) {
                        $query = new Query;
                        $str = '';
                        $rows = $query->from('pproduct')
                            ->match(new Expression(':match', ['match' => '@(description) ' . \Yii::$app->sphinx->escapeMatchValue($product->title)]))
                            ->all();
                        foreach ($rows as $row) {
                            if (!empty($row['id'])) {
                                // проставляем идентификатор
                                $str = $row['id'];

                                break;
                            }
                        }

                        // обновляем таблицу podruzka_product
                        if (!empty($str)) {
                            $productEntity = PodruzkaProduct::findOne(['id' => $str]);

                            if ($productEntity instanceof PodruzkaProduct) {
                                if (empty($productEntity->letu_id)) {
                                    $productEntity->letu_id = (string) $product->id;
                                    $productEntity->save();
                                }
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

    /**
     * @return int
     */
    public function actionIle()
    {
        $query = new Query;

        $rows = $query->from('lproduct')
            ->match(new Expression(':match', ['match' => '@(description)' . \Yii::$app->sphinx->escapeMatchValue('база         макияж     под')]))
            ->all();

        print_r($rows);
        print_r(IledebeauteProduct::findOne(['id' => $rows[0]['id']])->toArray());die;
        return $rows;
    }
}
