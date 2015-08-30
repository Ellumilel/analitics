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
 * @property string $rive_id
 * @property string $letu_id
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
     * @param $condition
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListBrand($condition)
    {
        return $this::find()->distinct()->select('brand')->where($condition)->all();
    }

    /**
     * @param $condition
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListSubBrand($condition)
    {
        return $this::find()->distinct()->select('sub_brand')->where($condition)->all();
    }

    /**
     * @param $condition
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListLine($condition)
    {
        return $this::find()->distinct()->select('line')->where($condition)->all();
    }

    /**
     * @param $condition
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListDetail($condition)
    {
        return $this::find()->distinct()->select('detail')->where($condition)->all();
    }

    /**
     * @param $condition
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListSubCategory($condition)
    {
        return $this::find()->distinct()->select('sub_category')->where($condition)->all();
    }

    /**
     * @param $condition
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListCategory($condition)
    {
        return $this::find()->distinct()->select('category')->where($condition)->all();
    }

    /**
     * @param $condition
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListGroup($condition)
    {
        return $this::find()->distinct()->select('group')->where($condition)->all();
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
}
