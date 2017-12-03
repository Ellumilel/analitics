<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "podruzka_product".
 *
 * @property integer $id
 * @property string $article
 * @property string $title
 * @property string $group
 * @property string $category
 * @property string $sub_category
 * @property string $detail
 * @property string $brand
 * @property string $sub_brand
 * @property string $line
 * @property number $price
 * @property number $ma_price
 * @property string $l_d_price
 * @property string $l_d_new_price
 * @property string $r_d_price
 * @property string $r_d_gold_price
 * @property string $e_d_price
 * @property string $e_d_new_price
 * @property string $i_d_price
 * @property string $i_d_new_price
 * @property string $arrival
 * @property string $ile_id
 * @property integer $i_id
 * @property string $rive_id
 * @property integer $r_id
 * @property string $letu_id
 * @property integer $l_id
 * @property integer $e_id
 * @property string $let_comment
 * @property string $riv_comment
 * @property string $ile_comment
 * @property string $eli_comment
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @property PodruzkaPrice[] $podruzkaPrices
 * @property IledebeauteProduct $i
 * @property LetualProduct $l
 * @property RivegaucheProduct $r
 */
class PodruzkaProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'podruzka_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article'], 'required'],
            [
                [
                    'price',
                    'ma_price',
                    'l_d_price',
                    'l_d_new_price',
                    'r_d_price',
                    'r_d_gold_price',
                    'e_d_price',
                    'e_d_new_price',
                    'i_d_price',
                    'i_d_new_price',
                ],
                'number',
            ],
            [['i_id', 'r_id', 'l_id', 'e_id'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['article'], 'string', 'max' => 100],
            [
                [
                    'group',
                    'title',
                    'category',
                    'sub_category',
                    'detail',
                    'brand',
                    'sub_brand',
                    'line',
                    'ile_id',
                    'rive_id',
                    'letu_id',
                    'let_comment',
                    'riv_comment',
                    'ile_comment',
                    'eli_comment',
                ],
                'string',
                'max' => 500,
            ],
            [['arrival'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article' => 'Артикул',
            'title' => 'Наименование',
            'group' => 'Группа',
            'category' => 'Категория',
            'sub_category' => 'Подкатегория',
            'detail' => 'Детализация',
            'brand' => 'Бренд',
            'sub_brand' => 'Подбренд',
            'line' => 'Линейка',
            'price' => 'Цена',
            'ma_price' => 'МА цена',
            'l_d_price' => 'L D Price',
            'l_d_new_price' => 'L D New Price',
            'r_d_price' => 'R D Price',
            'r_d_gold_price' => 'R D Gold Price',
            'e_d_price' => 'E D Price',
            'e_d_new_price' => 'E D New Price',
            'i_d_price' => 'I D Price',
            'i_d_new_price' => 'I D New Price',
            'arrival' => 'Приход',
            'l_id' => 'арт. Летуаль',
            'r_id' => 'арт. РивГош',
            'i_id' => 'арт. ИльДеБоте',
            'e_id' => 'арт. Элизэ',
            'l_title' => 'Летуаль наименование',
            'l_date' => 'Дата',
            'l_link' => 'Л ссылка',
            'l_article' => 'L_price',
            'l_new_price' => 'L_new_price',
            'l_old_price' => 'L_old_price',
            'r_title' => 'РивГош наименование',
            'r_price' => 'R_price',
            'r_blue_price' => 'R_blue_price',
            'r_gold_price' => 'R_gold_price',
            'r_link' => 'Р ссылка',
            'i_title' => 'ИльДэБотЭ наименование',
            'i_article' => 'И цена',
            'i_new_price' => 'I_new_price',
            'i_old_price' => 'I_old_price',
            'i_link' => 'И ссылка',
            'e_title' => 'Элизэ наименование',
            'e_new_price' => 'E_price',
            'e_old_price' => 'E_old_price',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'let_comment' => 'Комментарий Летуаль',
            'riv_comment' => 'Комментарий Ривгош',
            'ile_comment' => 'Комментарий ИлеДэБоте',
            'eli_comment' => 'Комментарий Элизэ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPodruzkaPrices()
    {
        return $this->hasMany(PodruzkaPrice::className(), ['article' => 'article']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getL()
    {
        return $this->hasOne(LetualProduct::className(), ['id' => 'l_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getR()
    {
        return $this->hasOne(RivegaucheProduct::className(), ['id' => 'r_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getI()
    {
        return $this->hasOne(IledebeauteProduct::className(), ['id' => 'i_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE()
    {
        return $this->hasOne(ElizeProduct::className(), ['id' => 'e_id']);
    }

    /**
     * @param $condition
     * @param bool|false $matching
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListBrand($condition, $matching = false)
    {
        $result = $this::find()->distinct()
            ->select('brand')
            ->where($condition);

        if ($matching) {
            $result->andWhere(' (r_id is not null or l_id is not null or i_id is not null or e_id is not null)');
        }

        return $result->orderBy('brand')->all();
    }

    /**
     * @param $condition
     * @param bool|false $matching
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListSubBrand($condition, $matching = false)
    {
        $result = $this::find()->distinct()
            ->select('sub_brand')
            ->where($condition);

        if ($matching) {
            $result->andWhere(' (r_id is not null or l_id is not null or i_id is not null or e_id is not null)');
        }

        return $result->orderBy('sub_brand')->all();
    }

    /**
     * @param $condition
     * @param bool|false $matching
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListLine($condition, $matching = false)
    {
        $result = $this::find()->distinct()
            ->select('line')
            ->where($condition);

        if ($matching) {
            $result->andWhere(' (r_id is not null or l_id is not null or i_id is not null or e_id is not null)');
        }

        return $result->orderBy('line')->all();
    }

    /**
     * @param $condition
     * @param bool|false $matching
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListDetail($condition, $matching = false)
    {
        $result = $this::find()->distinct()
            ->select('detail')
            ->where($condition);

        if ($matching) {
            $result->andWhere(' (r_id is not null or l_id is not null or i_id is not null or e_id is not null)');
        }

        return $result->orderBy('detail')->all();
    }

    /**
     * @param $condition
     * @param bool|false $matching
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListSubCategory($condition, $matching = false)
    {
        $result = $this::find()->distinct()
            ->select('sub_category')
            ->where($condition);

        if ($matching) {
            $result->andWhere(' (r_id is not null or l_id is not null or i_id is not null or e_id is not null)');
        }

        return $result->orderBy('sub_category')->all();
    }

    /**
     * @param $condition
     * @param bool|false $matching
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListCategory($condition, $matching = false)
    {
        $result = $this::find()->distinct()
            ->select('category')
            ->where($condition);

        if ($matching) {
            $result->andWhere(' (r_id is not null or l_id is not null or i_id is not null or e_id is not null)');
        }

        return $result->orderBy('category')->all();
    }

    /**
     * @param $condition
     * @param bool|false $matching
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListGroup($condition, $matching = false)
    {
        $result = $this::find()->distinct()
            ->select('group')
            ->where($condition);

        if ($matching) {
            $result->andWhere(' (r_id is not null or l_id is not null or i_id is not null or e_id is not null)');
        }

        return $result->orderBy('group')->all();
    }

    /**
     * @param $condition
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListArrival($condition)
    {
        return $this::find()->distinct()->select('arrival')->where($condition)->all();
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @param $offset
     * @param $limit
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getEntity($offset, $limit)
    {
        return $this::find()->offset($offset)->limit($limit)->all();
    }

    /**
     * @return array
     */
    public function getBrandAvgLetPrice()
    {
        $db = Yii::$app->getDb();
        $sql = 'SELECT
                    pp.brand, 
                    count(pp.article) as count,
                    AVG(pp.price) p_price,
                    AVG(ma_price) p_ma_price,
                    AVG(lp.old_price) l_old_price,
                    AVG(lp.new_price) l_new_price,
                    ((AVG(lp.old_price)/AVG(pp.price)-1) * 100) as l_old_percent,
                    ((AVG(lp.new_price)/AVG(pp.price)-1) * 100) as l_new_percent
                FROM podruzka_product pp
                INNER JOIN letual_product lp ON lp.id = pp.l_id
                WHERE pp.arrival="Разрешен"
                GROUP BY pp.brand';
        return $db->createCommand($sql)->queryAll();
    }

    /**
     * @return array
     */
    public function getBrandAvgRivPrice()
    {
        $db = Yii::$app->getDb();
        $sql = 'SELECT
                    pp.brand,
                    count(pp.article) as count, 
                    AVG(pp.price) p_price,
                    AVG(ma_price) p_ma_price,
                    AVG(rp.price) r_price,
                    AVG(rp.blue_price) r_blue_price,
                    AVG(rp.gold_price) r_gold_price,
                    ((AVG(rp.price)/AVG(pp.price)-1) * 100) as r_percent
                    FROM podruzka_product pp
                INNER JOIN rivegauche_product rp ON rp.id = pp.r_id
                WHERE pp.arrival="Разрешен"
                GROUP BY pp.brand';
        return $db->createCommand($sql)->queryAll();
    }

    /**
     * @return array
     */
    public function getBrandAvgIlePrice()
    {
        $db = Yii::$app->getDb();
        $sql = 'SELECT
                    pp.brand, 
                    count(pp.article) as count,
                    AVG(pp.price) p_price,
                    AVG(ma_price) p_ma_price,
                    AVG(ip.old_price) i_old_price,
                    AVG(ip.new_price) i_new_price,
                    ((AVG(ip.old_price)/AVG(pp.price)-1) * 100) as i_percent
                    FROM podruzka_product pp
                INNER JOIN iledebeaute_product ip ON ip.id = pp.i_id
                WHERE pp.arrival="Разрешен"
                GROUP BY pp.brand';
        return $db->createCommand($sql)->queryAll();
    }

    /**
     * @return array
     */
    public function getBrandAvgEliPrice()
    {
        $db = Yii::$app->getDb();
        $sql = 'SELECT
                    pp.brand, 
                    count(pp.article) as count,
                    AVG(pp.price) p_price,
                    AVG(ma_price) p_ma_price,
                    AVG(ep.old_price) e_old_price,
                    AVG(ep.new_price) e_new_price,
                    ((AVG(ep.new_price  )/AVG(pp.price)-1) * 100) as e_percent
                     FROM podruzka_product pp
                INNER JOIN elize_product ep ON ep.id = pp.e_id
                WHERE pp.arrival="Разрешен"
                GROUP BY pp.brand';
        return $db->createCommand($sql)->queryAll();
    }


    /**
     * @return array
     */
    public function getCategoryLetAvgPrice()
    {
        $db = Yii::$app->getDb();
        $sql = 'SELECT
                    pp.category, 
                    count(pp.article) as count,
                    AVG(pp.price) p_price,
                    AVG(ma_price) p_ma_price,
                    AVG(lp.old_price) l_old_price,
                    AVG(lp.new_price) l_new_price,
                    ((AVG(lp.old_price)/AVG(pp.price)-1) * 100) as l_old_percent,
                    ((AVG(lp.new_price)/AVG(pp.price)-1) * 100) as l_new_percent
                FROM podruzka_product pp
                INNER JOIN letual_product lp ON lp.id = pp.l_id
                WHERE pp.arrival="Разрешен"
                GROUP BY pp.category';
        return $db->createCommand($sql)->queryAll();
    }

    /**
     * @return array
     */
    public function getCategoryIleAvgPrice()
    {
        $db = Yii::$app->getDb();
        $sql = 'SELECT
                    pp.category, 
                    count(pp.article) as count,
                    AVG(pp.price) p_price,
                    AVG(ma_price) p_ma_price,
                    AVG(ip.old_price) i_old_price,
                    AVG(ip.new_price) i_new_price,
                    ((AVG(ip.old_price)/AVG(pp.price)-1) * 100) as i_percent
                    FROM podruzka_product pp
                INNER JOIN iledebeaute_product ip ON ip.id = pp.i_id
                WHERE pp.arrival="Разрешен"
                GROUP BY pp.category';
        return $db->createCommand($sql)->queryAll();
    }

    /**
     * @return array
     */
    public function getCategoryRivAvgPrice()
    {
        $db = Yii::$app->getDb();
        $sql = 'SELECT
                    pp.category,
                    count(pp.article) as count, 
                    AVG(pp.price) p_price,
                    AVG(ma_price) p_ma_price,
                    AVG(rp.price) r_price,
                    AVG(rp.blue_price) r_blue_price,
                    AVG(rp.gold_price) r_gold_price,
                    ((AVG(rp.price)/AVG(pp.price)-1) * 100) as r_percent
                    FROM podruzka_product pp
                INNER JOIN rivegauche_product rp ON rp.id = pp.r_id
                WHERE pp.arrival="Разрешен"
                GROUP BY pp.category';
        return $db->createCommand($sql)->queryAll();
    }

    /**
     * @return array
     */
    public function getCategoryEliAvgPrice()
    {
        $db = Yii::$app->getDb();
        $sql = 'SELECT
                    pp.category, 
                    count(pp.article) as count,
                    AVG(pp.price) p_price,
                    AVG(ma_price) p_ma_price,
                    AVG(ep.old_price) e_old_price,
                    AVG(ep.new_price) e_new_price,
                    ((AVG(ep.new_price  )/AVG(pp.price)-1) * 100) as e_percent
                     FROM podruzka_product pp
                INNER JOIN elize_product ep ON ep.id = pp.e_id
                WHERE pp.arrival="Разрешен"
                GROUP BY pp.category';
        return $db->createCommand($sql)->queryAll();
    }

    /**
     * @return array
     */
    public function getBrandCategoryLetAvgPrice()
    {
        $db = Yii::$app->getDb();
        $sql = 'SELECT
                    pp.category, 
                    pp.brand,
                    count(pp.article) as count,
                    AVG(pp.price) p_price,
                    AVG(ma_price) p_ma_price,
                    AVG(lp.old_price) l_old_price,
                    AVG(lp.new_price) l_new_price,
                    ((AVG(lp.old_price)/AVG(pp.price)-1) * 100) as l_old_percent,
                    ((AVG(lp.new_price)/AVG(pp.price)-1) * 100) as l_new_percent
                FROM podruzka_product pp
                INNER JOIN letual_product lp ON lp.id = pp.l_id
                WHERE pp.arrival="Разрешен"
                GROUP BY pp.category, pp.brand';
        return $db->createCommand($sql)->queryAll();
    }

    /**
     * @return array
     */
    public function getBrandCategoryIleAvgPrice()
    {
        $db = Yii::$app->getDb();
        $sql = 'SELECT
                    pp.category,
                    pp.brand,
                    count(pp.article) as count,
                    AVG(pp.price) p_price,
                    AVG(ma_price) p_ma_price,
                    AVG(ip.old_price) i_old_price,
                    AVG(ip.new_price) i_new_price,
                    ((AVG(ip.old_price)/AVG(pp.price)-1) * 100) as i_percent
                    FROM podruzka_product pp
                INNER JOIN iledebeaute_product ip ON ip.id = pp.i_id
                WHERE pp.arrival="Разрешен"
                GROUP BY pp.category, pp.brand';
        return $db->createCommand($sql)->queryAll();
    }

    /**
     * @return array
     */
    public function getBrandCategoryRivAvgPrice()
    {
        $db = Yii::$app->getDb();
        $sql = 'SELECT
                    pp.category,
                    pp.brand,
                    count(pp.article) as count, 
                    AVG(pp.price) p_price,
                    AVG(ma_price) p_ma_price,
                    AVG(rp.price) r_price,
                    AVG(rp.blue_price) r_blue_price,
                    AVG(rp.gold_price) r_gold_price,
                    ((AVG(rp.price)/AVG(pp.price)-1) * 100) as r_percent
                    FROM podruzka_product pp
                INNER JOIN rivegauche_product rp ON rp.id = pp.r_id
                WHERE pp.arrival="Разрешен"
                GROUP BY pp.category, pp.brand';
        return $db->createCommand($sql)->queryAll();
    }

    /**
     * @return array
     */
    public function getBrandCategoryEliAvgPrice()
    {
        $db = Yii::$app->getDb();
        $sql = 'SELECT
                    pp.category, 
                    pp.brand,
                    count(pp.article) as count,
                    AVG(pp.price) p_price,
                    AVG(ma_price) p_ma_price,
                    AVG(ep.old_price) e_old_price,
                    AVG(ep.new_price) e_new_price,
                    ((AVG(ep.new_price  )/AVG(pp.price)-1) * 100) as e_percent
                     FROM podruzka_product pp
                INNER JOIN elize_product ep ON ep.id = pp.e_id
                WHERE pp.arrival="Разрешен"
                GROUP BY pp.category, pp.brand';
        return $db->createCommand($sql)->queryAll();
    }
    
    /**
     * Обновляет ценовую разницу при изменении ценовых данных
     *
     * @return int
     * @throws \yii\db\Exception
     */
    public static function updatePriceDiff()
    {
        $db = Yii::$app->getDb();
        $sql = '
        UPDATE podruzka_product upp
            INNER JOIN(
            SELECT 
                pp.id as p_id, 
                (pp.price - rp.price) as r_d_price, 
                (pp.price - rp.gold_price) as r_d_gold_price, 
                (pp.price - lp.new_price) as l_d_new_price, 
                (pp.price - lp.old_price) as l_d_price, 
                (pp.price - ip.new_price) as i_d_new_price, 
                (pp.price - ip.old_price) as i_d_price, 
                (pp.price - ep.new_price) as e_d_new_price, 
                (pp.price - ep.old_price) as e_d_price 
                FROM podruzka_product pp
                LEFT JOIN rivegauche_product rp on pp.r_id = rp.id
                LEFT JOIN iledebeaute_product ip on pp.i_id = ip.id
                LEFT JOIN elize_product ep on pp.e_id = ep.id
                LEFT JOIN letual_product lp on pp.l_id = lp.id
            ) v
            on upp.id = v.p_id
        SET 
        upp.r_d_price = v.r_d_price,
        upp.r_d_gold_price = v.r_d_gold_price,
        upp.l_d_price = v.l_d_price,
        upp.l_d_new_price = v.l_d_new_price,
        upp.i_d_price = v.i_d_price,
        upp.i_d_new_price = v.i_d_new_price,
        upp.e_d_price = v.e_d_price,
        upp.e_d_new_price = v.e_d_new_price
        WHERE upp.id = v.p_id';

        return $db->createCommand($sql)->execute();
    }
}
