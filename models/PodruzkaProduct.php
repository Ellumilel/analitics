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
    public function getListBrand()
    {
        return $this::find()->distinct()->select('brand')->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListSubBrand()
    {
        return $this::find()->distinct()->select('sub_brand')->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListLine()
    {
        return $this::find()->distinct()->select('line')->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListDetail()
    {
        return $this::find()->distinct()->select('detail')->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListSubCategory()
    {
        return $this::find()->distinct()->select('sub_category')->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListCategory()
    {
        return $this::find()->distinct()->select('category')->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListGroup()
    {
        return $this::find()->distinct()->select('group')->all();
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
