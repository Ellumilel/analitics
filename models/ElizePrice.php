<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\sphinx\ActiveRecord;

/**
 * This is the model class for table "elize_price".
 *
 * @property integer $id
 * @property string $article
 * @property string $new_price
 * @property string $old_price
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @property ElizeProduct $article0
 */
class ElizePrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elize_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article'], 'required'],
            [['new_price', 'old_price'], 'number'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['article'], 'string', 'max' => 100],
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
            'new_price' => 'New Price',
            'old_price' => 'Old Price',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
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
     * @return \yii\db\ActiveQuery
     */
    public function getArticle0()
    {
        return $this->hasOne(ElizeProduct::className(), ['article' => 'article']);
    }
}
