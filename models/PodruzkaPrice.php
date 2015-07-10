<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "podruzka_price".
 *
 * @property integer $id
 * @property string $article
 * @property string $price
 * @property string $ma_price
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @property PodruzkaProduct $article0
 */
class PodruzkaPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'podruzka_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article', 'price', 'ma_price'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['article', 'price', 'ma_price'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article' => 'Article',
            'price' => 'Price',
            'ma_price' => 'Ma Price',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle0()
    {
        return $this->hasOne(PodruzkaProduct::className(), ['article' => 'article']);
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
