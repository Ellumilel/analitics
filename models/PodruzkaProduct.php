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
 * @property string $group
 * @property string $category
 * @property string $sub_category
 * @property string $detail
 * @property string $brand
 * @property string $sub_brand
 * @property string $line
 * @property string $price
 * @property string $ma_price
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
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['article', 'price', 'ma_price'], 'string', 'max' => 100],
            [['group', 'category', 'sub_category', 'detail', 'brand', 'sub_brand', 'line'], 'string', 'max' => 500]
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
            'group' => 'Группа',
            'category' => 'Категория',
            'sub_category' => 'Подкатегория',
            'detail' => 'Детализация',
            'brand' => 'Бренд',
            'sub_brand' => 'Подбренд',
            'line' => 'Линейка',
            'price' => 'Цена',
            'ma_price' => 'МА цена',
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
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListBrand($condition)
    {
        return $this::find()->distinct()->select('brand')->where($condition)->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListSubBrand($condition)
    {
        return $this::find()->distinct()->select('sub_brand')->where($condition)->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListLine($condition)
    {
        return $this::find()->distinct()->select('line')->where($condition)->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListDetail($condition)
    {
        return $this::find()->distinct()->select('detail')->where($condition)->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListSubCategory($condition)
    {
        return $this::find()->distinct()->select('sub_category')->where($condition)->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListCategory($condition)
    {
        return $this::find()->distinct()->select('category')->where($condition)->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListGroup($condition)
    {
        return $this::find()->distinct()->select('group')->where($condition)->all();
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
}
