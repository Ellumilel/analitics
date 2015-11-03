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
 * @property string $arrival
 * @property string $ile_id
 * @property integer $i_id
 * @property string $rive_id
 * @property integer $r_id
 * @property string $letu_id
 * @property integer $l_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @property PodruzkaPrice[] $podruzkaPrices
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
            [['price', 'ma_price'], 'number'],
            [['i_id', 'r_id', 'l_id'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['article'], 'string', 'max' => 100],
            [['group', 'title', 'category', 'sub_category', 'detail', 'brand', 'sub_brand', 'line', 'ile_id', 'rive_id', 'letu_id'], 'string', 'max' => 500],
            [['arrival'], 'string', 'max' => 40]
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
            'arrival' => 'Приход',
            'l_id' => 'арт. Летуаль',
            'r_id' => 'арт. РивГош',
            'i_id' => 'арт. ИльДеБоте',
            'l_title' => 'Л наименование',
            'l_date' => 'Дата',
            'l_link' => 'Л ссылка',
            'l_article' => 'Л цена',
            'l_new_price' => 'Л цена скидка',
            'l_old_price' => 'Л цена',
            'r_title' => 'Р наименование',
            'r_price' => 'Р цена',
            'r_blue_price' => 'Р цена блю',
            'r_gold_price' => 'Р цена голд',
            'r_link' => 'Р ссылка',
            'i_title' => 'И наименование',
            'i_article' => 'И цена',
            'i_new_price' => 'И цена скидка',
            'i_old_price' => 'И цена',
            'i_link' => 'И ссылка',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
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
            $result->andWhere(' (r_id is not null or l_id is not null or i_id is not null)');
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
            $result->andWhere(' (r_id is not null or l_id is not null or i_id is not null)');
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
            $result->andWhere(' (r_id is not null or l_id is not null or i_id is not null)');
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
            $result->andWhere(' (r_id is not null or l_id is not null or i_id is not null)');
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
            $result->andWhere(' (r_id is not null or l_id is not null or i_id is not null)');
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
            $result->andWhere(' (r_id is not null or l_id is not null or i_id is not null)');
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
            $result->andWhere(' (r_id is not null or l_id is not null or i_id is not null)');
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
    public function getBrandAvgPrice()
    {
        $db = Yii::$app->getDb();
        $sql = 'Select
                pp.brand,
                count(pp.brand) as count,
                avg(pp.price) p_price,
                AVG(ma_price) p_ma_price,
                lp.old_price l_old_price,
                lp.new_price l_new_price,
                rp.price r_price,
                rp.blue_price r_blue_price,
                rp.gold_price r_gold_price,
                ip.old_price i_old_price,
                ip.new_price i_new_price
                FROM podruzka_product pp
                LEFT JOIN (select AVG(old_price) old_price, AVG(new_price) new_price, brand from letual_product GROUP BY brand) lp on lp.brand = pp.brand
                LEFT JOIN (select AVG(price) price, AVG(blue_price) blue_price, AVG(gold_price) gold_price, brand from rivegauche_product GROUP BY brand) rp on rp.brand = pp.brand
                LEFT JOIN (select AVG(new_price) new_price, AVG(old_price) old_price, brand from iledebeaute_product GROUP BY brand) ip on ip.brand = pp.brand
                where arrival="Разрешен"
                group by brand;';
        return $db->createCommand($sql)->queryAll();
    }

    /**
     * @return array
     */
    public function getCategoryAvgPrice()
    {
        $db = Yii::$app->getDb();
        $sql = 'SELECT
                pp.category,
                count(pp.category) as count,
                AVG(pp.price) p_price,
                AVG(ma_price) p_ma_price,
                AVG(lp.old_price) l_old_price,
                AVG(lp.new_price) l_new_price,
                AVG(rp.price) r_price,
                AVG(rp.blue_price) r_blue_price,
                AVG(rp.gold_price) r_gold_price,
                AVG(ip.old_price) i_old_price,
                AVG(ip.new_price) i_new_price
                FROM podruzka_product pp
                LEFT JOIN letual_product lp on lp.id = pp.l_id
                LEFT JOIN rivegauche_product rp on rp.id = pp.r_id
                LEFT JOIN iledebeaute_product ip on ip.id = pp.i_id
                WHERE arrival="Разрешен"
                group by pp.category';
        return $db->createCommand($sql)->queryAll();
    }
    /**
     * @return array
     */
    public function getBrandCategoryAvgPrice()
    {
        $db = Yii::$app->getDb();
        $sql = 'SELECT
                pp.brand,
                pp.category,
                count(pp.brand+pp.category) as count,
                AVG(pp.price) p_price,
                AVG(ma_price) p_ma_price,
                AVG(lp.old_price) l_old_price,
                AVG(lp.new_price) l_new_price,
                AVG(rp.price) r_price,
                AVG(rp.blue_price) r_blue_price,
                AVG(rp.gold_price) r_gold_price,
                AVG(ip.old_price) i_old_price,
                AVG(ip.new_price) i_new_price
                FROM podruzka_product pp
                LEFT JOIN letual_product lp on lp.id = pp.l_id
                LEFT JOIN rivegauche_product rp on rp.id = pp.r_id
                LEFT JOIN iledebeaute_product ip on ip.id = pp.i_id
                WHERE arrival="Разрешен"
                group by pp.brand, pp.category';
        return $db->createCommand($sql)->queryAll();
    }
}
