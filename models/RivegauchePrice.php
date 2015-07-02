<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rivegauche_price".
 *
 * @property integer $id
 * @property string $article
 * @property string $gold_price
 * @property string $blue_price
 * @property string $price
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @property RivegaucheProduct $article0
 */
class RivegauchePrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rivegauche_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['article'], 'string', 'max' => 100],
            [['gold_price', 'blue_price', 'price'], 'string', 'max' => 500]
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
            'gold_price' => 'Gold Price',
            'blue_price' => 'Blue Price',
            'price' => 'Price',
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
        return $this->hasOne(RivegaucheProduct::className(), ['article' => 'article']);
    }
}
